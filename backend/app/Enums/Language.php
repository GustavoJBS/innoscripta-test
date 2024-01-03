<?php

namespace App\Enums;

enum Language: string
{
    case AR = 'ar';
    case DE = 'de';
    case EN = 'en';
    case ES = 'es';
    case FR = 'fr';
    case HE = 'he';
    case IT = 'it';
    case NL = 'nl';
    case NO = 'no';
    case PT = 'pt';
    case RU = 'ru';
    case SV = 'sv';
    case ZH = 'zh';

    public static function listLanguages(): array
    {
        return collect(self::cases())
            ->map(fn (self $language) => [
                'label' => $language->getCompleteName(),
                'value' => $language->value,
            ])->toArray();
    }

    public function getCompleteName(): string
    {
        return match ($this->value) {
            'ar'    => 'Arabic',
            'de'    => 'German',
            'en'    => 'English',
            'es'    => 'Spanish',
            'fr'    => 'French',
            'he'    => 'Hebrew',
            'it'    => 'Italian',
            'nl'    => 'Dutch',
            'no'    => 'Norwegian',
            'pt'    => 'Portuguese',
            'ru'    => 'Russian',
            'sv'    => 'Swedish',
            'zh'    => 'Chinese',
            default => 'English',
        };
    }
}
