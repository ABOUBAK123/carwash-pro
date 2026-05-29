<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsSetting extends Model
{
    protected $fillable = ['content'];

    public static function instance(): self
    {
        return static::firstOrCreate([], [
            'content' => "# Conditions Générales d'Utilisation\n\nBienvenue sur CarWash Pro.\n\nEn utilisant nos services, vous acceptez les présentes conditions générales.\n\n## 1. Utilisation du service\n\nLe service est fourni tel quel...\n\n## 2. Responsabilités\n\nNous déclinons toute responsabilité...",
        ]);
    }
}
