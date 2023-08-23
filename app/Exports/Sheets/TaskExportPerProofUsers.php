<?php

namespace App\Exports\Sheets;

use App\Entities\InternalEvent;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;


class TaskExportPerProofUsers implements FromCollection, WithTitle, WithHeadings
{

    /**
     * @return Builder
     */
    public function collection()
    {
        return DB::table('task as a')
                ->select('a.id',
                    DB::raw('CASE 
                        WHEN RIGHT(a.created_at, 8) > "16:00:00" THEN
                            CASE
                                WHEN TIMESTAMPDIFF(MINUTE, CONCAT(LEFT(DATE_ADD(a.created_at, INTERVAL 1 DAY), 10), " 08:00:00"), NOW()) < 1440 THEN "INICIO"
                                WHEN TIMESTAMPDIFF(MINUTE, CONCAT(LEFT(DATE_ADD(a.created_at, INTERVAL 1 DAY), 10), " 08:00:00"), NOW()) BETWEEN 1440 AND 2880 THEN "VENCE HOY"
                                WHEN TIMESTAMPDIFF(MINUTE, CONCAT(LEFT(DATE_ADD(a.created_at, INTERVAL 1 DAY), 10), " 08:00:00"), NOW()) > 2880 THEN "VENCIDO"
                                ELSE "VENCIDO"
                            END
                        WHEN RIGHT(a.created_at, 8) < "16:00:00" THEN
                            CASE
                                WHEN TIMESTAMPDIFF(MINUTE, a.created_at, NOW()) < 1440 THEN "INICIO"
                                WHEN TIMESTAMPDIFF(MINUTE, a.created_at, NOW()) BETWEEN 1440 AND 2880 THEN "VENCE HOY"
                                WHEN TIMESTAMPDIFF(MINUTE, a.created_at, NOW()) > 2880 THEN "VENCIDO"
                                ELSE "VENCIDO"
                            END
                    END AS NS'))
                ->leftJoin('gestiones as b', 'a.id_unico', '=', 'b.id_unico')
                ->get();
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Mi segunda hoja prueba';
    }

    public function headings(): array
    {
        return ['columna111', 'columna222'];
    }

}