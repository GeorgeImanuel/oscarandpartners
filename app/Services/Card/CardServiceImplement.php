<?php

namespace App\Services\Card;

use Illuminate\Support\Facades\DB;
use LaravelEasyRepository\Service;
use Illuminate\Container\AttributesDB;
use App\Repositories\Card\CardRepository;
use App\Repositories\Part\PartRepository;
use App\Repositories\Object\ObjectRepository;
use App\Repositories\Policy\PolicyRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Coverage\CoverageRepository;
use App\Repositories\ObjectPart\ObjectPartRepository;
use App\Repositories\ObjectType\ObjectTypeRepository;
use App\Repositories\PolicyInsurance\PolicyInsuranceRepository;

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
    protected $objectTypeRepository;
    protected $objectRepository;
    protected $partRepository;
    protected $objectPartRepository;
    protected $policyRepository;
    protected $coverageRepository;
    protected $policyCoverageRepository;
    protected $policyInsuranceRepository;
    protected $paymentRepository;

    public function __construct(
        CardRepository $mainRepository,
        ObjectTypeRepository $objectTypeRepository,
        ObjectRepository $objectRepository,
        PartRepository $partRepository,
        ObjectPartRepository $objectPartRepository,
        PolicyRepository $policyRepository,
        CoverageRepository $coverageRepository,
        PolicyInsuranceRepository $policyInsuranceRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->mainRepository = $mainRepository;
        $this->objectTypeRepository = $objectTypeRepository;
        $this->objectRepository = $objectRepository;
        $this->partRepository = $partRepository;
        $this->objectPartRepository = $objectPartRepository;
        $this->policyRepository = $policyRepository;
        $this->coverageRepository = $coverageRepository;
        $this->policyInsuranceRepository = $policyInsuranceRepository;
        $this->paymentRepository = $paymentRepository;
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

            //Find or Create Object
            if (!isset($data['object_id']) || $data['object_id'] == null) {
                //Find or Create Object Type
                $objectType = $this->objectTypeRepository->find($data['object_type_id']);
                if (!$objectType) {
                    $objectType = $this->objectTypeRepository->create([
                        'name' => $data['object_type_id'],
                    ]);
                }
                $data['object_type_id'] = $objectType->id;

                //Create Object
                $object = $this->objectRepository->create([
                    'object_type_id' => $data['object_type_id'],
                    'name'           => $data['object_name'],
                    'address'        => $data['object_address'],
                ]);

                //Find or Create Part
                if (isset($data['part_id']) && is_array($data['part_id'])) {
                    foreach ($data['part_id'] as $partIndex => $part_id) {
                        $partId = $data['part_id'][$partIndex];
                        $part = $this->partRepository->find($partId);
                        if (!$part) {
                            $part = $this->partRepository->create([
                                'name' => $data['part_id'][$partIndex],
                            ]);
                            $partId = $part->id;
                        }

                        //Create Object Part
                        $this->objectPartRepository->create([
                            'object_id' => $object->id,
                            'part_id'   => $partId,
                            'value'     => $data['value'][$partIndex] ?? 0,
                        ]);
                    }
                }
                $data['object_id'] = $object->id;
            }
            //Find or Create Policy
            if (!isset($data['policy_id']) || $data['policy_id'] == null) {
                $policy = $this->policyRepository->create([
                    'policy_number'       => $data['policy_number'],
                    'object_id'           => $data['object_id'],
                    'coverage_code'       => $data['coverage_code'],
                    'coverage_percentage' => $data['coverage_percentage'] / 100,
                    'started_date'        => $data['started_date'],
                    'ended_date'          => $data['ended_date'],
                ]);

                //Assign Coverage
                $coverage = $this->coverageRepository->find($data['coverage_code']);

                if (!$coverage) {
                    $this->coverageRepository->create([
                        'code'  => $data['coverage_code'],
                        'name' => $data['coverage_code'],
                    ]);
                }

                //Assign Insurances
                if (isset($data['insurance_id']) && is_array($data['insurance_id'])) {
                    foreach ($data['insurance_id'] as $index => $insuranceId) {
                        $this->policyInsuranceRepository->create([
                            'policy_id'    => $policy->id,
                            'insurance_id' => $insuranceId,
                            'percentage'  => $data['percentage'][$index] / 100,
                            'is_main'     => isset($data['is_main']) && in_array($insuranceId, $data['is_main']),
                        ]);
                    }
                }

                $data['policy_id'] = $policy->id;
            }

            //Create Card
            $card = $this->mainRepository->create([
                'client_id'             => $data['client_id'],
                'policy_id'             => $data['policy_id'],
                'card_type_id'          => $data['card_type_id'] ?? null,
                'card_status_id'        => config('constants.card_status.active'),
                'collector_type_id'     => $data['collector_type_id'] ?? config('constants.collector_type.staff'),
                'parent_card_id'        => $data['parent_card_id'] ?? null,
                'payment_type_id'       => config('constants.payment_type.penuh'),
                'created_by'            => $data['created_by'] ?? auth()->id(),
                'premi_percentages'     => $data['premi_percentages'] / 100 ?? 0,
                'tax_percentages'       => $data['tax_percentages'] / 100 ?? 0,
                'commision_percentages' => (float)($data['commision_percentages'] / 100 ?? 0),
                'object_value'          => (float)($data['object_value']),
                'policy_fee'            => (float)($data['policy_fee'] ?? 0),
                'discount'              => (float)($data['discount'] ?? 0),
                'production_value'      => (float)($data['production_value'] ?? 0),
                'premi_value'           => (float)($data['premi_value'] ?? 0),
                'commision_bruto'       => (float)($data['commision_bruto'] ?? 0),
                'commision_netto'       => (float)($data['commision_netto'] ?? 0),
                'tax_value'             => (float)($data['tax_value'] ?? 0),
                'client_pay'            => (float)($data['client_pay'] ?? 0),
                'insurance_pay'         => (float)($data['insurance_pay'] ?? 0),

            ]);

            //Create Payment
            $payment = $this->paymentRepository->create([
                'card_id'                  => $card->id,
                'sequence'                 => 1,
                'created_by'               => $data['created_by'] ?? auth()->id(),
                'amount'                   => $data['client_pay'] ?? 0,
                'client_payment_status_id' => config('constants.payment_status.pending'),
                'deadline_at'              => $data['deadline_at'] ?? null,
            ]);

            DB::commit();
            return $card;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update production card
     */
    public function updateCard($id, array $data)
    {
        // Get input values
        $premium_gross = $data['harga_pertanggungan'] ?? 0;
        $commission_pct = $data['tarif_premi'] ?? 0;
        $admin_fee = $data['biaya_polis'] ?? 0;
        $commission_percentage = $data['persentase_komisi'] ?? 0;
        $tax_percentage = $data['pajak_komisi'] ?? 0;
        $discount = $data['diskon_dari_komisi'] ?? 0;

        // Calculate all fields
        $nilai_produksi = $premium_gross * ($commission_pct / 100);
        $total_premi = $nilai_produksi + $admin_fee;
        $komisi_bruto = $nilai_produksi * ($commission_percentage / 100);
        $nilai_pajak = $komisi_bruto * ($tax_percentage / 100);
        $komisi_bersih_awal = $komisi_bruto - $nilai_pajak;
        $tagihan_ke_nasabah = $total_premi - $discount;
        $sisa_komisi_agen = $komisi_bersih_awal - $discount;
        $netto_bayar_asuransi = $total_premi - $komisi_bersih_awal;

        // Map form fields to database columns
        $mappedData = [
            'client_id' => $data['client_id'],
            'policy_id' => $data['policy_id'],
            'card_status_id' => $data['card_status_id'],

            // Date mapping
            'start_date' => $data['periode_mulai'] ?? null,
            'end_date' => $data['periode_akhir'] ?? null,

            // Input fields
            'premium_gross' => $premium_gross,
            'commission_pct' => $commission_pct,
            'admin_fee' => $admin_fee,
            'commission_percentage' => $commission_percentage,
            'tax_percentage' => $tax_percentage,
            'discount' => $discount,

            // Calculated fields
            'commission_amount' => $komisi_bruto,
            'net_to_insurance' => $netto_bayar_asuransi,
            'premi_amount' => $nilai_produksi,
            'polis_amount' => $admin_fee,
            'client_total' => $total_premi,
            'commission_amount_initial' => $komisi_bersih_awal,
            'tax_amount' => $nilai_pajak,
            'discount_amount' => $discount,
            'commission_total' => $sisa_komisi_agen,

            // Payment info
            'insurance_payment_status_id' => $data['insurance_payment_status_id'] ?? null,
            'payment_type_id' => $data['payment_type_id'],
            'insurance_paid_at' => $data['insurance_paid_at'] ?? null,
            'insurance_evident_number' => $data['insurance_evident_number'] ?? null,
        ];

        return $this->mainRepository->update($id, $mappedData);
    }

    public function getAllWithFilter(array $data)
    {
        return $this->mainRepository->getAllWithFilter($data);
    }
}
