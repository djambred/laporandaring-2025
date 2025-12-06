<?php

namespace App\Filament\Admin\Resources\JadwalResource\Pages;

use App\Filament\Admin\Resources\JadwalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwal extends ViewRecord
{
    protected static string $resource = JadwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_pdf')
                ->label('Download Laporan PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(fn (): string => route('jadwal.download-pdf', $this->record))
                ->openUrlInNewTab(),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
