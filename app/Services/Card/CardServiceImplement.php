<?php

namespace App\Services\Card;

use Illuminate\Support\Facades\DB;
use LaravelEasyRepository\Service;
use App\Repositories\Card\CardRepository;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Object\ObjectRepository;
use App\Repositories\Policy\PolicyRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Coverage\CoverageRepository;
use App\Repositories\Interest\InterestRepository;
use App\Repositories\Relation\RelationRepository;
use App\Repositories\Insurance\InsuranceRepository;
use App\Repositories\ObjectInterest\ObjectInterestRepository;
use App\Repositories\PolicyCoverage\PolicyCoverageRepository;
use App\Repositories\PolicyInsurance\PolicyInsuranceRepository;
use App\Repositories\PolicyObject\PolicyObjectRepository;
use App\Repositories\ObjectOccupation\ObjectOccupationRepository;

class CardServiceImplement extends Service implements CardService
{
    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title = "";
    /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;
    protected $clientRepository;
    protected $relationRepository;
    protected $objectOccupationRepository;
    protected $objectRepository;
    protected $interestRepository;
    protected $objectInterestRepository;
    protected $policyRepository;
    protected $coverageRepository;
    protected $policyCoverageRepository;
    protected $policyInsuranceRepository;
    protected $policyObjectRepository;
    protected $insuranceRepository;
    protected $paymentRepository;

    public function __construct(
        CardRepository $mainRepository,
        ClientRepository $clientRepository,
        RelationRepository $relationRepository,
        ObjectOccupationRepository $objectOccupationRepository,
        ObjectRepository $objectRepository,
        InterestRepository $interestRepository,
        ObjectInterestRepository $objectInterestRepository,
        PolicyRepository $policyRepository,
        CoverageRepository $coverageRepository,
        PolicyCoverageRepository $policyCoverageRepository,
        PolicyInsuranceRepository $policyInsuranceRepository,
        PolicyObjectRepository $policyObjectRepository,
        InsuranceRepository $insuranceRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->mainRepository             = $mainRepository;
        $this->clientRepository           = $clientRepository;
        $this->relationRepository         = $relationRepository;
        $this->objectOccupationRepository = $objectOccupationRepository;
        $this->objectRepository           = $objectRepository;
        $this->interestRepository         = $interestRepository;
        $this->objectInterestRepository   = $objectInterestRepository;
        $this->policyRepository           = $policyRepository;
        $this->coverageRepository         = $coverageRepository;
        $this->policyCoverageRepository   = $policyCoverageRepository;
        $this->policyInsuranceRepository  = $policyInsuranceRepository;
        $this->policyObjectRepository     = $policyObjectRepository;
        $this->insuranceRepository        = $insuranceRepository;
        $this->paymentRepository          = $paymentRepository;
    }

    public function findWithRelation($id)
    {
        return $this->mainRepository->findWithRelation($id);
    }

    public function getAllParent()
    {
        return $this->mainRepository->getAllParent();
    }

    /**
     * Get all cards with relatiocns
     */
    public function getAllWithRelations()
    {
        return $this->mainRepository->getAllWithRelations();
    }

    /**
     * Create production card
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();
            //1. Handle Client Creation (if requested)
            $data['client_id'] = $this->findOrCreateClient($data);
            
            // 2. Handle Policy Creation (if requested)
            $data['policy_id'] = $this->findOrCreatePolicy($data);

            // 5. Create Card
            $card = $this->mainRepository->create([
                'client_id'       => $data['client_id'],
                'policy_id'       => $data['policy_id'],
                'card_type_id'    => $data['card_type_id'],
                'card_status_id'  => config('constants.card_status.active'),
                'parent_card_id'  => $data['parent_card_id'] ?? null,
                'payment_type_id' => $data['payment_type_id'],
                'created_by'      => auth()->id(),

                'object_value'         => (float)($data['object_value'] ?? 0),
                'premi_percentages'    => ($data['premi_percentages'] ?? 0) / 100,
                'commision_percentage' => ($data['commision_percentage'] ?? 0) / 100,
                'policy_fee'           => (float)($data['policy_fee'] ?? 0),
                'discount_percentage'  => (float)(($data['discount_percentage'] ?? 0) / 100),
                'discount_value'       => (float)($data['discount_value'] ?? 0),

                'production_value' => (float)($data['production_value'] ?? 0),
                'premi_value'      => (float)($data['premi_value'] ?? 0),
                'commision_bruto'  => (float)($data['commision_bruto'] ?? 0),
                'commision_netto'  => (float)($data['commision_netto'] ?? 0),
                'tax'              => (float)($data['tax'] ?? 0),
                'discount_agent'   => (float)($data['discount_agent'] ?? 0),
                'client_pay'       => (float)($data['client_pay'] ?? 0),
                'insurance_pay'    => (float)($data['insurance_pay'] ?? 0),
            ]);

            // Create Payment(s)
            // Check if installments array exists (cicilan/installment)
            if (isset($data['installments']) && is_array($data['installments']) && count($data['installments']) > 0) {
                // Create multiple payments for installments
                foreach ($data['installments'] as $installment) {
                    $this->paymentRepository->create([
                        'card_id'            => $card->id,
                        'sequence'           => $installment['sequence'] ?? 1,
                        'created_by'         => $data['created_by'] ?? auth()->id(),
                        'amount'             => (float)($installment['amount'] ?? 0),
                        'payment_status_id'  => config('constants.payment_status.pending'),
                        'deadline_at'        => $installment['due_date'] ?? null,
                    ]);
                }
            } else {
                // Create single payment for full payment
                $payment = $this->paymentRepository->create([
                    'card_id'            => $card->id,
                    'sequence'           => 1,
                    'created_by'         => $data['created_by'] ?? auth()->id(),
                    'amount'             => $data['client_pay'] ?? 0,
                    'payment_status_id'  => config('constants.payment_status.pending'),
                    'deadline_at'        => $data['deadline_at'] ?? null,
                ]);
            }

            DB::commit();
            return $card;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function findOrCreateRelation(array $data): int
    {
        $relation = $this->relationRepository->find($data['relation_id']);
        if (!$relation) {
            $relation = $this->relationRepository->create([
                'code' => $data['relation_id'],
                'name' => $data['relation_id'] ?? null,
            ]);
        }
        return $relation->id;
    }

    /**
     * Create new client with optional relation
     */
    private function findOrCreateClient(array $data): int
    {
        $client = $this->clientRepository->find($data['client_id']);
        if (!$client) {
            $client = $this->clientRepository->create([
                'relation_id' => $this->findOrCreateRelation($data),
                'id_name'     => $data['client_id'],
                'id_address'  => $data['id_address'] ?? null,
            ]);
        }
        return $client->id;
    }

    /**
     * Create new coverage
     */
    private function findOrCreateCoverage(string $coverageCode)
    {
        $coverage = $this->coverageRepository->find($coverageCode);
        if (!$coverage) {
            $coverage = $this->coverageRepository->create([
                'code' => $coverageCode,
                'name' => $coverageCode,
            ]);
        }
        return $coverage->code;
    }

    /**
     * Create new insurance
     */
    private function findOrCreateInsurance(int $insuranceId)
    {
        $insurance = $this->insuranceRepository->find($insuranceId);
        if (!$insurance) {
            $insurance = $this->insuranceRepository->create([
                'name' => $insuranceId
            ]);
        }
        return $insurance->id;
    }

    /**
     * Create new object occupation
     */
    private function findOrCreateObjectOccupation(int $objectOccupationId): int
    {
        $occupation = $this->objectOccupationRepository->find($objectOccupationId);
        if (!$occupation) {
            $occupation = $this->objectOccupationRepository->create([
                'name' => $objectOccupationId
            ]);
        }
        return $occupation->id;
    }

    /**
     * Create new interest
     */
    private function findOrCreateInterest(int $interestId): int
    {
        $interest = $this->interestRepository->find($interestId);
        if (!$interest) {
            $interest = $this->interestRepository->create([
                'name' => $interestId
            ]);
        }
        return $interest->id;
    }

    /**
     * Create new object with occupation and interests
     */
    private function findOrCreateObject(array $data, $objectIndex): int
    {
        // If object_id is numeric, it's an existing object ID - return it directly
        if (isset($data['object_id'][$objectIndex]) && is_numeric($data['object_id'][$objectIndex])) {
            $existingObject = $this->objectRepository->find($data['object_id'][$objectIndex]);
            if ($existingObject) {
                return $existingObject->id;
            }
        }
        
        // Otherwise, create a new object
        // Handle both array and single value for object_occupation_id
        $objectOccupationId = null;
        if (isset($data['object_occupation_id'])) {
            if (is_array($data['object_occupation_id']) && isset($data['object_occupation_id'][$objectIndex])) {
                $objectOccupationId = $data['object_occupation_id'][$objectIndex];
            } elseif (!is_array($data['object_occupation_id'])) {
                $objectOccupationId = $data['object_occupation_id'];
            }
        }
        
        if (!$objectOccupationId) {
            throw new \Exception("Object occupation ID is required for object index {$objectIndex}");
        }
        
        // Handle both array and single value for object_keterangan
        $keterangan = null;
        if (isset($data['object_keterangan'])) {
            if (is_array($data['object_keterangan']) && isset($data['object_keterangan'][$objectIndex])) {
                $keterangan = $data['object_keterangan'][$objectIndex];
            } elseif (!is_array($data['object_keterangan'])) {
                $keterangan = $data['object_keterangan'];
            }
        }
        if (!$keterangan && isset($data['object_address'])) {
            if (is_array($data['object_address']) && isset($data['object_address'][$objectIndex])) {
                $keterangan = $data['object_address'][$objectIndex];
            } elseif (!is_array($data['object_address'])) {
                $keterangan = $data['object_address'];
            }
        }
        
        // Handle both array and single value for tjh
        $tjh = 0;
        if (isset($data['tjh'])) {
            if (is_array($data['tjh']) && isset($data['tjh'][$objectIndex])) {
                $tjh = (float)$data['tjh'][$objectIndex];
            } elseif (!is_array($data['tjh'])) {
                $tjh = (float)$data['tjh'];
            }
        }
        
        // Create object
        $object = $this->objectRepository->create([
            'object_occupation_id' => $this->findOrCreateObjectOccupation($objectOccupationId),
            'name'                 => $data['object_id'][$objectIndex],
            'keterangan'           => $keterangan,
            'tjh'                  => $tjh,
        ]);

        // Create object interests if exists
        if (isset($data['interest_id']) && is_array($data['interest_id'])) {
            $interestIds = $data['interest_id'];
            $interestValues = $data['interest_value'] ?? [];
            
            // Ensure interest values is an array
            if (!is_array($interestValues)) {
                $interestValues = [$interestValues];
            }
            
            // Filter valid interests and pair them with values
            $validInterests = [];
            foreach ($interestIds as $interestId) {
                if ($interestId && !empty($interestId) && $interestId !== 'null' && $interestId !== null) {
                    $validInterests[] = $interestId;
                }
            }
            
            // Create interest records - pair valid interests with values sequentially
            foreach ($validInterests as $idx => $validInterestId) {
                $value = isset($interestValues[$idx]) ? (float)$interestValues[$idx] : 0;
                
                $this->objectInterestRepository->create([
                    'object_id'   => $object->id,
                    'interest_id' => $this->findOrCreateInterest($validInterestId),
                    'value'       => $value,
                ]);
            }
        }

        return $object->id;
    }

    /**
     * Create new policy with insurances
     */
    private function findOrCreatePolicy(array $data)
    {
        $policy = $this->policyRepository->find($data['policy_id']);
        if (!$policy) {
            $policy = $this->policyRepository->create([
                'policy_number'       => $data['policy_id'],
                'currency_code'       => $data['currency_code'],
                'started_date'        => $data['started_date'],
                'ended_date'          => $data['ended_date'],
            ]);

            // Assign coverages
            foreach ($data['coverage_code'] as $index => $coverageCode) {
                $this->policyCoverageRepository->create([
                    'policy_id'    => $policy->id,
                    'coverage_code'  => $this->findOrCreateCoverage($coverageCode),
                    'percentage'   => ($data['coverage_percentage'][$index] ?? 0) / 100,
                ]);
            }

            // Assign insurances
            foreach ($data['insurance_id'] as $index => $insuranceId) {
                $this->policyInsuranceRepository->create([
                    'policy_id'    => $policy->id,
                    'insurance_id' => $this->findOrCreateInsurance($insuranceId),
                    'percentage'   => ($data['insurance_percentage'][$index] ?? 0) / 100,
                    'is_main'      => isset($data['is_main'][$index]) && $data['is_main'][$index] ? 1 : 0,
                ]);
            }

            // Assign objects
            if (isset($data['object_id']) && is_array($data['object_id'])) {
                foreach ($data['object_id'] as $index => $objectId) {
                    // Skip undefined or null entries
                    if ($index === 'undefined' || $objectId === null) {
                        continue;
                    }
                    
                    $this->policyObjectRepository->create([
                        'policy_id'           => $policy->id,
                        'object_id'           => $this->findOrCreateObject($data, $index)
                    ]);
                }
            }
        }

        return $policy->id;
    }

    /**
     * Update production card
     */
    public function updateCard($id, array $data)
    {
        // 1. Ambil Input (Gunakan nama field yang sesuai dengan Form Blade)
        // Parse float/int agar aman kalkulasinya
        $object_value   = (float)($data['object_value'] ?? 0);
        $premi_pct      = (float)($data['premi_percentages'] ?? 0) / 100; // Dibagi 100 karena dari form raw (misal 2.5)
        $policy_fee     = (float)($data['policy_fee'] ?? 0);
        $comm_pct       = (float)($data['commision_percentage'] ?? 0) / 100;
        $discount_pct   = (float)($data['discount_percentage'] ?? 0) / 100;
        $discount_value = (float)($data['discount_value'] ?? 0);
        $tjh_value      = (float)($data['tjh_value'] ?? 0);
        $discount_agent     = (float)($data['discount_agent'] ?? 0); // Nama input form: discount_agent

        // 2. Kalkulasi Ulang (Sesuai Logic JavaScript)
        // Nilai Produksi = (Pertanggungan * %Premi) + TJH
        $production_value = ($object_value * $premi_pct) + $tjh_value;

        // Total Premi = Produksi + Biaya Polis
        $premi_value      = $production_value + $policy_fee;

        // Komisi Bruto = Produksi * %Komisi
        $commision_bruto  = $production_value * $comm_pct;

        // Pajak = Komisi Bruto * %Pajak
        // (Atau ambil dari input 'tax' jika user input manual pajak per asuransi)
        $tax = (float)($data['tax'] ?? 0);

        // Komisi Bersih = Bruto - Pajak - Diskon Agen
        $commision_netto  = $commision_bruto - $tax - $discount_agent;

        // Tagihan Nasabah (Client Pay) = Total Premi - Diskon Nasabah
        $client_pay       = $premi_value - $discount_value;

        // Bayar Asuransi (Insurance Pay) = Total Premi - Komisi Bersih
        $insurance_pay    = $premi_value - $commision_netto;


        // 3. Mapping ke Kolom Database (HARUS SAMA PERSIS dengan method Create)
        $mappedData = [
            // Field Relasi
            'client_id'      => $data['client_id'],
            'policy_id'      => $data['policy_id'],
            'card_type_id'   => $data['card_type_id'] ?? null,
            'card_status_id' => $data['card_status_id'] ?? config('constants.card_status.active'),
            'parent_card_id' => $data['parent_card_id'] ?? null,

            // Field Date
            'start_date' => $data['periode_mulai'] ?? null, // Pastikan name di form 'periode_mulai' atau sesuaikan
            'end_date'   => $data['periode_akhir'] ?? null,

            // Field Angka (Sesuai nama kolom di DB & method Create)
            'object_value'          => $object_value,
            'premi_percentages'     => $premi_pct,
            'policy_fee'            => $policy_fee,
            'commision_percentage' => $comm_pct,
            'discount_percentage'  => $discount_pct,
            'discount_value'        => $discount_value,
            'tjh_value'             => $tjh_value, // Jangan lupa field ini

            // Hasil Kalkulasi
            'production_value'      => $production_value,
            'premi_value'           => $premi_value,      // DB: premi_value (Bukan premi_amount)
            'commision_bruto'       => $commision_bruto,  // DB: commision_bruto (Bukan commission_amount)
            'tax'                   => $tax,              // DB: tax (Bukan tax_amount)
            'discount_agent'            => $discount_agent,       // DB: discount_agent (Bukan discount_amount)
            'commision_netto'       => $commision_netto,  // DB: commision_netto

            // Field yang hilang sebelumnya:
            'client_pay'            => $client_pay,       // DB: client_pay (Bukan client_total)
            'insurance_pay'         => $insurance_pay,    // DB: insurance_pay (Bukan net_to_insurance)

            // Payment Info
            'payment_type_id'       => $data['payment_type_id'] ?? null,
            // 'insurance_payment_status_id' => ... (sesuaikan jika ada)
        ];

        return $this->mainRepository->update($id, $mappedData);
    }

    public function getAllWithFilter(array $data)
    {
        return $this->mainRepository->getAllWithFilter($data);
    }
}
