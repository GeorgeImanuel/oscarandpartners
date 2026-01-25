<?php

namespace App\Services\Card;

use Illuminate\Support\Facades\DB;
use LaravelEasyRepository\Service;
use App\Repositories\Card\CardRepository;
use App\Repositories\Interest\InterestRepository;
use App\Repositories\Object\ObjectRepository;
use App\Repositories\Policy\PolicyRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Coverage\CoverageRepository;
use App\Repositories\ObjectInterest\ObjectInterestRepository;
use App\Repositories\ObjectOccupation\ObjectOccupationRepository;
use App\Repositories\PolicyInsurance\PolicyInsuranceRepository;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Relation\RelationRepository;

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
        PolicyInsuranceRepository $policyInsuranceRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->mainRepository = $mainRepository;
        $this->clientRepository = $clientRepository;
        $this->relationRepository = $relationRepository;
        $this->objectOccupationRepository = $objectOccupationRepository;
        $this->objectRepository = $objectRepository;
        $this->interestRepository = $interestRepository;
        $this->objectInterestRepository = $objectInterestRepository;
        $this->policyRepository = $policyRepository;
        $this->coverageRepository = $coverageRepository;
        $this->policyInsuranceRepository = $policyInsuranceRepository;
        $this->paymentRepository = $paymentRepository;
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
            if (isset($data['create_new_client']) && $data['create_new_client']) {
                $data['client_id'] = $this->createNewClient($data);
            }

            // 2. Handle Object Creation (if requested)
            if (isset($data['create_new_object']) && $data['create_new_object']) {
                $data['object_id'] = $this->createNewObject($data);
            }

            //Find or Create Policy
            if (!isset($data['policy_id']) || $data['policy_id'] == null) {
                $policy = $this->policyRepository->create([
                    'policy_number'       => $data['policy_number'],
                    'object_id'           => $data['object_id'],
                    'coverage_id'         => $data['coverage_id'],
                    'coverage_percentage' => $data['coverage_percentage'] / 100,
                    'started_date'        => $data['started_date'],
                    'ended_date'          => $data['ended_date'],
                ]);

                //Assign Coverage - no longer needed since we're using ID
                // Coverage should already exist with the given ID

                //Assign Insurances
                if (isset($data['insurance_id']) && is_array($data['insurance_id'])) {
                    foreach ($data['insurance_id'] as $index => $insuranceId) {
                        $taxValue = 0;
                        if (isset($data['insurance_tax'][$insuranceId])) {
                            $taxValue = (float)$data['insurance_tax'][$insuranceId];
                        }
                        
                        $this->policyInsuranceRepository->create([
                            'policy_id'    => $policy->id,
                            'insurance_id' => $insuranceId,
                            'percentage'  => $data['percentage'][$index] / 100,
                            'is_main'     => isset($data['is_main']) && in_array($insuranceId, $data['is_main']),
                            'tax'         => $taxValue,
                        ]);
                    }
                }

                $data['policy_id'] = $policy->id;
            } else {
                // Update tax untuk existing policy insurances
                if (isset($data['insurance_tax']) && is_array($data['insurance_tax'])) {
                    foreach ($data['insurance_tax'] as $insuranceId => $taxValue) {
                        DB::table('policy_insurances')
                            ->where('policy_id', $data['policy_id'])
                            ->where('insurance_id', $insuranceId)
                            ->update(['tax' => (float)$taxValue]);
                    }
                }
            }

            // 4. Validate all required IDs are present
            if (!isset($data['client_id']) || !isset($data['policy_id'])) {
                throw new \Exception('Client ID and Policy ID are required');
            }

            // 5. Create Card
            $card = $this->mainRepository->create([
                'client_id'             => $data['client_id'],
                'policy_id'             => $data['policy_id'],
                'card_type_id'          => $data['card_type_id'] ?? null,
                'card_status_id'        => config('constants.card_status.active'),
                'parent_card_id'        => $data['parent_card_id'] ?? null,
                'payment_type_id'       => $data['payment_type_id'] ?? config('constants.payment_type.penuh'),
                'created_by'            => $data['created_by'] ?? auth()->id(),
                'premi_percentages'     => $data['premi_percentages'] / 100 ?? 0,
                'commision_percentages' => (float)($data['commision_percentages'] / 100 ?? 0),
                'object_value'          => (float)($data['object_value']),
                'policy_fee'            => (float)($data['policy_fee'] ?? 0),
                'discount_percentages'  => (float)($data['discount_percentage'] / 100 ?? 0),
                'discount_value'        => (float)($data['discount_value'] ?? 0),
                'production_value'      => (float)($data['production_value'] ?? 0),
                'premi_value'           => (float)($data['premi_value'] ?? 0),
                'commision_bruto'       => (float)($data['commision_bruto'] ?? 0),
                'commision_netto'       => (float)($data['commision_netto'] ?? 0),
                'tax'                   => (float)($data['tax'] ?? 0),
                'discount_agent'        => (float)($data['discount_agent'] ?? 0),
                'client_pay'            => (float)($data['client_pay'] ?? 0),
                'insurance_pay'         => (float)($data['insurance_pay'] ?? 0),
            ]);

            //Create Payment(s)
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

    /**
     * Create new client with optional relation
     */
    private function createNewClient(array $data): int
    {
        // Create relation if needed
        if (isset($data['create_new_relation']) && $data['create_new_relation']) {
            $relation = $this->relationRepository->create([
                'code' => $data['relation_code'],
                'name' => $data['relation_name'] ?? null,
            ]);
            $data['relation_id'] = $relation->id;
        }

        $client = $this->clientRepository->create([
            'relation_id' => $data['relation_id'] ?? null,
            'name'        => $data['client_name'],
            'address'     => $data['client_address'] ?? null,
            'phone'       => $data['client_phone'] ?? null,
            'email'       => $data['client_email'] ?? null,
        ]);

        return $client->id;
    }

    /**
     * Create new object with occupation and interests
     */
    private function createNewObject(array $data): int
    {
        // Handle object occupation
        if (isset($data['object_occupation_id'])) {
            $objectOccupation = $this->objectOccupationRepository->find($data['object_occupation_id']);
            if (!$objectOccupation) {
                $objectOccupation = $this->objectOccupationRepository->create([
                    'name' => $data['object_occupation_name'] ?? $data['object_occupation_id'],
                ]);
            }
            $data['object_occupation_id'] = $objectOccupation->id;
        }

        // Create object
        $object = $this->objectRepository->create([
            'object_occupation_id' => $data['object_occupation_id'],
            'name'                 => $data['object_name'],
            'keterangan'           => $data['object_address'] ?? null,
        ]);

        // Create object interests
        if (isset($data['interest_id']) && is_array($data['interest_id'])) {
            foreach ($data['interest_id'] as $index => $interestId) {
                $interest = $this->interestRepository->find($interestId);
                if (!$interest) {
                    $interest = $this->interestRepository->create([
                        'name' => $data['interest_name'][$index] ?? $interestId,
                    ]);
                    $interestId = $interest->id;
                }

                $this->objectInterestRepository->create([
                    'object_id'   => $object->id,
                    'interest_id' => $interestId,
                    'value'       => $data['value'][$index] ?? 0,
                ]);
            }
        }

        return $object->id;
    }

    /**
     * Create new policy with insurances
     */
    private function createNewPolicy(array $data): int
    {
        $policy = $this->policyRepository->create([
            'policy_number'       => $data['policy_number'],
            'object_id'           => $data['object_id'],
            'coverage_id'         => $data['coverage_id'],
            'coverage_percentage' => $data['coverage_percentage'] / 100,
            'started_date'        => $data['started_date'],
            'ended_date'          => $data['ended_date'],
        ]);

        // Assign insurances
        if (isset($data['insurance_id']) && is_array($data['insurance_id'])) {
            foreach ($data['insurance_id'] as $index => $insuranceId) {
                $this->policyInsuranceRepository->create([
                    'policy_id'    => $policy->id,
                    'insurance_id' => $insuranceId,
                    'percentage'   => $data['percentage'][$index] / 100,
                    'is_main'      => isset($data['is_main']) && in_array($insuranceId, $data['is_main']),
                ]);
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
        $comm_pct       = (float)($data['commision_percentages'] ?? 0) / 100;
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
            'commision_percentages' => $comm_pct,
            'discount_percentages'  => $discount_pct,
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