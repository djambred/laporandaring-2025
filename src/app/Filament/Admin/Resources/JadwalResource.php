<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\JadwalResource\Pages;
use App\Filament\Admin\Resources\JadwalResource\RelationManagers;
use App\Models\Jadwal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class JadwalResource extends Resource
{
    protected static ?string $model = Jadwal::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = -1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('program_studi_id')
                    ->label('Program Studi')
                    ->relationship('programstudi', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('mata_kuliah_id')
                    ->label('Mata Kuliah')
                    ->relationship('matakuliah', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('dosen_id')
                    ->label('Dosen')
                    ->relationship('dosen', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->default(now()),
                Forms\Components\TextInput::make('jam')
                    ->label('Jam')
                    ->required()
                    ->placeholder('Contoh: 08:00 - 10:00')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif untuk Absensi Mahasiswa')
                    ->helperText('Jika aktif, jadwal ini akan muncul di halaman absensi mahasiswa')
                    ->default(false)
                    ->inline(false),
                Forms\Components\FileUpload::make('dokumentasi')
                    ->label('Dokumentasi Perkuliahan')
                    ->helperText('Upload screenshot/foto saat mengajar (bisa multiple)')
                    ->multiple()
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                    ])
                    ->maxFiles(10)
                    ->maxSize(5120)
                    ->directory('dokumentasi-perkuliahan')
                    ->visibility('public')
                    ->downloadable()
                    ->openable()
                    ->reorderable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('jam')
                    ->label('Jam')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('programstudi.nama')
                    ->label('Program Studi')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable()
                    ->tooltip(fn (Jadwal $record): string => $record->is_active ? 'Aktif - Muncul di halaman absensi' : 'Tidak Aktif - Tidak muncul di halaman absensi'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua Jadwal')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Hanya Tidak Aktif')
                    ->native(false),
                Tables\Filters\SelectFilter::make('program_studi_id')
                    ->label('Program Studi')
                    ->relationship('programstudi', 'nama'),
                Tables\Filters\SelectFilter::make('dosen_id')
                    ->label('Dosen')
                    ->relationship('dosen', 'nama'),
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal_dari')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('tanggal_sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal_dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['tanggal_sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn (Jadwal $record): string => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn (Jadwal $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Jadwal $record): string => $record->is_active ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Jadwal $record): string => $record->is_active ? 'Nonaktifkan Jadwal?' : 'Aktifkan Jadwal?')
                    ->modalDescription(fn (Jadwal $record): string => $record->is_active
                        ? 'Jadwal ini tidak akan muncul di halaman absensi mahasiswa.'
                        : 'Jadwal ini akan muncul di halaman absensi mahasiswa.')
                    ->action(function (Jadwal $record) {
                        $record->update(['is_active' => !$record->is_active]);
                    })
                    ->successNotificationTitle(fn (Jadwal $record): string => $record->is_active
                        ? 'Jadwal berhasil diaktifkan!'
                        : 'Jadwal berhasil dinonaktifkan!'),
                Tables\Actions\Action::make('download_pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Jadwal $record): string => route('jadwal.download-pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan Jadwal')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Jadwal berhasil diaktifkan!'),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan Jadwal')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Jadwal berhasil dinonaktifkan!'),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AbsensisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwals::route('/'),
            'create' => Pages\CreateJadwal::route('/create'),
            'view' => Pages\ViewJadwal::route('/{record}'),
            'edit' => Pages\EditJadwal::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getModelLabel(): string
    {
        return 'Jadwal';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Jadwal';
    }
}
