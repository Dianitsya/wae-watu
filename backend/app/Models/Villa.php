<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villa extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
        'capacity',
        'available_units',
        'total_units',
        'price_per_night',
        'description',
        'features',
        'image_url',
    ];

    protected $casts = [
        'features' => 'array',
        'price_per_night' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::saved(function ($villa) {
            try {
                $prop = \Illuminate\Support\Facades\DB::table('properties')->where('slug', 'wae-watu-reef-resort')->first();
                if (!$prop) return;

                $unit = \Illuminate\Support\Facades\DB::table('units')->where('slug', $villa->slug)->first();
                if ($unit) {
                    \Illuminate\Support\Facades\DB::table('units')->where('id', $unit->id)->update([
                        'name' => $villa->name,
                        'description' => $villa->description ?? '',
                        'base_price' => (int) $villa->price_per_night,
                        'max_occupancy' => (int) ($villa->capacity ?? 2),
                        'status' => $villa->status === 'available' ? 'active' : 'inactive',
                        'updated_at' => now(),
                    ]);
                    $unitId = $unit->id;
                } else {
                    $unitClass = \Illuminate\Support\Facades\DB::table('unit_classes')->where('property_id', $prop->id)->first();
                    $classId = $unitClass ? $unitClass->id : 1;
                    $unitId = \Illuminate\Support\Facades\DB::table('units')->insertGetId([
                        'property_id' => $prop->id,
                        'unit_class_id' => $classId,
                        'name' => $villa->name,
                        'slug' => $villa->slug,
                        'description' => $villa->description ?? '',
                        'base_price' => (int) $villa->price_per_night,
                        'max_occupancy' => (int) ($villa->capacity ?? 2),
                        'status' => $villa->status === 'available' ? 'active' : 'inactive',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if ($unitId) {
                    $priceCat = \Illuminate\Support\Facades\DB::table('price_categories')->where('property_id', $prop->id)->first();
                    $priceCatId = $priceCat ? $priceCat->id : 43;

                    $pc = \Illuminate\Support\Facades\DB::table('price_classes')->where('unit_id', $unitId)->first();
                    if ($pc) {
                        \Illuminate\Support\Facades\DB::table('price_classes')->where('id', $pc->id)->update([
                            'price' => (int) $villa->price_per_night,
                            'price_category_id' => $priceCatId,
                            'status' => 'active',
                            'updated_at' => now(),
                        ]);
                    } else {
                        $interval = \Illuminate\Support\Facades\DB::table('intervals')->whereRaw("LOWER(name) = 'daily'")->first();
                        \Illuminate\Support\Facades\DB::table('price_classes')->insert([
                            'unit_id' => $unitId,
                            'price_category_id' => $priceCatId,
                            'interval_id' => $interval ? $interval->id : 2,
                            'price' => (int) $villa->price_per_night,
                            'type' => 'normal',
                            'status' => 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Sync physical rooms (unit_details) in PMS to match available_units count
                    $targetCount = max(0, (int) ($villa->available_units ?? 28));
                    if ($villa->status !== 'available') {
                        $targetCount = 0; // If marked sold out or maintenance, 0 available rooms
                    }

                    $currentActive = \Illuminate\Support\Facades\DB::table('unit_details')
                        ->where('unit_id', $unitId)
                        ->where('status', 'available')
                        ->whereNull('deleted_at')
                        ->count();

                    if ($currentActive < $targetCount) {
                        $diff = $targetCount - $currentActive;
                        $totalEver = \Illuminate\Support\Facades\DB::table('unit_details')->where('unit_id', $unitId)->count();
                        for ($i = 1; $i <= $diff; $i++) {
                            $num = $totalEver + $i;
                            \Illuminate\Support\Facades\DB::table('unit_details')->insert([
                                'unit_id' => $unitId,
                                'property_id' => $prop->id,
                                'name' => $villa->name . ' #' . sprintf('%02d', $num),
                                'unit_number' => $num,
                                'floor' => 1,
                                'status' => 'available',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    } else if ($currentActive > $targetCount) {
                        $excess = $currentActive - $targetCount;
                        $idsToDisable = \Illuminate\Support\Facades\DB::table('unit_details')
                            ->where('unit_id', $unitId)
                            ->where('status', 'available')
                            ->whereNull('deleted_at')
                            ->latest('id')
                            ->take($excess)
                            ->pluck('id');
                        \Illuminate\Support\Facades\DB::table('unit_details')->whereIn('id', $idsToDisable)->update(['status' => 'inactive']);
                    }

                    // Sync photo to properties and property_photos in PMS
                    $fullImageUrl = $villa->image_url;
                    if ($fullImageUrl) {
                        \Illuminate\Support\Facades\DB::table('properties')->where('id', $prop->id)->update([
                            'image' => $fullImageUrl,
                            'updated_at' => now(),
                        ]);

                        $existingPhoto = \Illuminate\Support\Facades\DB::table('property_photos')->where('property_id', $prop->id)->first();
                        if ($existingPhoto) {
                            \Illuminate\Support\Facades\DB::table('property_photos')->where('id', $existingPhoto->id)->update([
                                'path' => $fullImageUrl,
                                'updated_at' => now(),
                            ]);
                        } else {
                            \Illuminate\Support\Facades\DB::table('property_photos')->insert([
                                'property_id' => $prop->id,
                                'path' => $fullImageUrl,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error syncing Villa to Terra PMS: ' . $e->getMessage());
            }
        });
    }

    public function getImageUrlAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, 'data:')) {
            return $value;
        }
        return url($value);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
