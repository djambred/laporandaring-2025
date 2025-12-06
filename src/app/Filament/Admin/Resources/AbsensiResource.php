<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AbsensiResource\Pages;
use App\Filament\Admin\Resources\AbsensiResource\RelationManagers;
use App\Models\Absensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jadwal_id')
                    ->label('Jadwal')
                    ->relationship('jadwal', 'id')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $matakuliah = $record->matakuliah->nama ?? 'N/A';
                        $tanggal = $record->tanggal instanceof \Carbon\Carbon
                            ? $record->tanggal->format('d M Y')
                            : \Carbon\Carbon::parse($record->tanggal)->format('d M Y');
                        return "{$matakuliah} - {$tanggal} {$record->jam}";
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Pilih jadwal perkuliahan'),
                Forms\Components\Select::make('mahasiswa_id')
                    ->label('Mahasiswa')
                    ->relationship('mahasiswa', 'nama')
                    ->searchable(['nama', 'npm'])
                    ->getOptionLabelFromRecordUsing(fn ($record) =>
                        $record->npm . ' - ' . $record->nama
                    )
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                    ])
                    ->required()
                    ->default('alpha'),
                Forms\Components\DateTimePicker::make('waktu_absen')
                    ->label('Waktu Absen')
                    ->default(now())
                    ->native(false),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('foto_absen')
                    ->label('Foto Absen')
                    ->image()
                    ->directory('absensi-foto')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('lokasi')
                    ->label('Lokasi (GPS)')
                    ->maxLength(255)
                    ->placeholder('Contoh: -6.200000, 106.816666'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jadwal.tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwal.jam')
                    ->label('Jam')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jadwal.matakuliah.nama')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('mahasiswa.npm')
                    ->label('NPM')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'sakit' => 'info',
                        'alpha' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?? 'alpha'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_absen')
                    ->label('Waktu Absen')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\ImageColumn::make('foto_absen')
                    ->label('Foto')
                    ->circular()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpha' => 'Alpha',
                    ]),
                Tables\Filters\SelectFilter::make('jadwal_id')
                    ->label('Mata Kuliah')
                    ->relationship('jadwal.matakuliah', 'nama'),
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
                                !empty($data['tanggal_dari']),
                                fn (Builder $query) =>
                                    $query->whereHas('jadwal', fn($q) => $q->whereDate('tanggal', '>=', $data['tanggal_dari']))
                            )
                            ->when(
                                !empty($data['tanggal_sampai']),
                                fn (Builder $query) =>
                                    $query->whereHas('jadwal', fn($q) => $q->whereDate('tanggal', '<=', $data['tanggal_sampai']))
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
            'view' => Pages\ViewAbsensi::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereDate('created_at', today())->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Absensi hari ini';
    }

    public static function getModelLabel(): string
    {
        return 'Absensi';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Absensi';
    }
}
