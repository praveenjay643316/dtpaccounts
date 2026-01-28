<?php

require_once '../../config/config.php';

class AjaxGetTax_Rate extends ConfigClass
{
    public $db = NULL;

    function __construct()
    {
        $this->db = $this->db_connect();
    }

    public function getTaxAmount()
    {
      
         $dcode = $this->getCurrentDistrictCode();
        $lbcode = $this->getCurrentLocalBodyCode();


        // Decode POST values safely
        $pay_mode = isset($_POST['pay_mode'])
            ? base64_decode($_POST['pay_mode'])
            : 0;

        $bill_collector_id = isset($_POST['bill_collector_id'])
            ? base64_decode($_POST['bill_collector_id'])
            : 0;

        if (!empty($_POST['collection_date'])) {
            list($dd, $mm, $yy) = explode('-', base64_decode($_POST['collection_date']));
            $collection_date = $yy . '-' . $mm . '-' . $dd;
        } else {
            $collection_date = date('Y-m-d');
        }

        $sql = "
            SELECT
            /* ---------- PROPERTY TAX (HEAD-WISE) ---------- */
            COALESCE((
                SELECT json_agg(
                    json_build_object(
                        'tax_head', b.tax_name,
                        'tax_amount', b.tax_amount
                    )
                )
                FROM propertytax.t_pp_collection_assessment a
                LEFT JOIN propertytax.t_pp_collection_demand b
                    ON a.receipt_assessment_serial_no = b.receipt_assessment_serial_no
                WHERE a.collectiondate::date = :collection_date
                  AND a.dcode = :dcode
                  AND a.lbcode = :lbcode
                  AND a.paymenttypeid = :pay_mode
                  AND a.profile_id = :profile_id
                  AND a.del_flag IS NULL
                  AND b.del_flag IS NULL
            ), '[]') AS propertytax_details,

            /* ---------- WATER TAX ---------- */
            COALESCE((
                SELECT SUM(b.totalamount)
                FROM watertax.t_wt_collection_assessment a
                LEFT JOIN watertax.t_wt_collection_demand b
                    ON a.receipt_assessment_serial_no = b.receipt_assessment_serial_no
                WHERE a.collectiondate::date = :collection_date
                  AND a.dcode = :dcode
                  AND a.lbcode = :lbcode
                  AND a.paymenttypeid = :pay_mode
                  AND a.profile_id = :profile_id
                  AND a.del_flag IS NULL
                  AND b.del_flag IS NULL
            ), 0) AS watertax,

            /* ---------- PROFESSIONAL TAX ---------- */
            COALESCE((
                SELECT SUM(b.totalamount)
                FROM professionaltax.t_prof_collection_assessment a
                LEFT JOIN professionaltax.t_prof_collection_demand b
                    ON a.receipt_assessment_serial_no = b.receipt_assessment_serial_no
                WHERE a.collectiondate::date = :collection_date
                  AND a.dcode = :dcode
                  AND a.lbcode = :lbcode
                  AND a.paymenttypeid = :pay_mode
                  AND a.profile_id = :profile_id
                  AND a.del_flag IS NULL
                  AND b.del_flag IS NULL
            ), 0) AS professiontax,

            /* ---------- NON TAX ---------- */
            COALESCE((
                SELECT SUM(b.totalamount)
                FROM nontax.t_nt_collection_assessment a
                LEFT JOIN nontax.t_nt_collection_demand b
                    ON a.receipt_assessment_serial_no = b.receipt_assessment_serial_no
                WHERE a.collectiondate::date = :collection_date
                  AND a.dcode = :dcode
                  AND a.lbcode = :lbcode
                  AND a.paymenttypeid = :pay_mode
                  AND a.profile_id = :profile_id
                  AND a.del_flag IS NULL
                  AND b.del_flag IS NULL
            ), 0) AS nontax,

            /* ---------- TRADE LICENSE ---------- */
            COALESCE((
                SELECT SUM(b.totalamount)
                FROM tradelicense.t_tl_collection_assessment a
                LEFT JOIN tradelicense.t_tl_collection_demand b
                    ON a.receipt_assessment_serial_no = b.receipt_assessment_serial_no
                WHERE a.collectiondate::date = :collection_date
                  AND a.dcode = :dcode
                  AND a.lbcode = :lbcode
                  AND a.paymenttypeid = :pay_mode
                  AND a.profile_id = :profile_id
                  AND a.del_flag IS NULL
                  AND b.del_flag IS NULL
            ), 0) AS tradelicense
        ";

        $params = [
            ':dcode' => $dcode,
            ':lbcode' => $lbcode,
            ':pay_mode' => $pay_mode,
            ':collection_date' => $collection_date,
            ':profile_id' => $bill_collector_id
        ];

        $result = $this->prepare($sql, $params, 4);

        /* ---------- PROPERTY TAX CALCULATION ---------- */
        $propertytax_total = 0;
        $interest_sum = 0;

        $propertytax_details = json_decode($result['propertytax_details'], true);

        foreach ($propertytax_details as $tax) {
            if (trim($tax['tax_head']) === 'Interest Amount') {
                $interest_sum += $tax['tax_amount'];
            } else {
                $propertytax_total += $tax['tax_amount'];
            }
        }

        $interest_final = round($interest_sum, 0, PHP_ROUND_HALF_UP);
        $final_propertytax = $propertytax_total + $interest_final;

        /* ---------- GRAND TOTAL ---------- */
        $grand_total =
            $final_propertytax +
            $result['watertax'] +
            $result['professiontax'] +
            $result['nontax'] +
            $result['tradelicense'];

        /* ---------- JSON RESPONSE ---------- */
        echo json_encode([
            'success' => true,
            'grand_total' => (float)$grand_total
        ]);
        exit;
    }
}

/* ---------- EXECUTION ---------- */
$AjaxGetTax_Rate = new AjaxGetTax_Rate();
$AjaxGetTax_Rate->getTaxAmount();
