<?php

declare(strict_types=1);

class SiteSetting
{
    public const KEYS = ['address', 'hours', 'map_embed'];

    public function allKeyed(): array
    {
        $rows = db()->query('SELECT `key`, `value` FROM site_settings')->fetchAll();

        $out = [];
        foreach ($rows as $row) {
            $out[(string) $row['key']] = (string) $row['value'];
        }

        return $out;
    }

    public function setMany(array $data): void
    {
        $stmt = db()->prepare(
            'INSERT INTO site_settings (`key`, `value`, updated_at) VALUES (?, ?, NOW())
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), updated_at = NOW()'
        );
        foreach ($data as $key => $value) {
            if (! in_array($key, self::KEYS, true)) {
                continue;
            }
            $stmt->execute([$key, (string) $value]);
        }

        if (function_exists('site_config_flush')) {
            site_config_flush();
        }
    }

    public function ensureDefaults(): void
    {
        $count = (int) db()->query('SELECT COUNT(*) FROM site_settings')->fetchColumn();
        if ($count > 0) {
            return;
        }

        $defaults = [];
        foreach (self::KEYS as $key) {
            $value = app_config($key);
            if (is_string($value) && $value !== '') {
                $defaults[$key] = $value;
            }
        }

        if ($defaults !== []) {
            $this->setMany($defaults);
        }
    }
}
