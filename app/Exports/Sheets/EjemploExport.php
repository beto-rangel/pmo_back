<?php

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Sheet1 implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Aquí puedes obtener tus datos para la hoja 1
        return collect([
            ['Nombre', 'Email', 'Teléfono'],
            ['John Doe', 'johndoe@example.com', '123456789'],
            ['Jane Smith', 'janesmith@example.com', '987654321'],
        ]);
    }

    public function headings(): array
    {
        return ['Nombre', 'Email', 'Teléfono'];
    }
}

class Sheet2 implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Aquí puedes obtener tus datos para la hoja 2
        return collect([
            ['Producto', 'Cantidad', 'Precio'],
            ['Producto 1', 10, 100],
            ['Producto 2', 5, 200],
        ]);
    }

    public function headings(): array
    {
        return ['Producto', 'Cantidad', 'Precio'];
    }
}