<?php

declare(strict_types=1);

namespace Haikara\Verifier\Types;

/**
 * ファイル名のルール
 */
class File extends CharString
{
    /**
     * 型変換
     * basenameのみ切り出す（ディレクトリトラバーサル対策）
     *
     * @param mixed $value
     * @return string|false
     */
    public static function filter(mixed $value): bool|string
    {
        $filteredValue = filter_var($value);
        return is_string($filteredValue) ? pathinfo($filteredValue)['basename'] ?? '' : $filteredValue;
    }

    /**
     * 拡張子を検証
     *
     * @param string[] $extensions
     * @return static
     */
    public function ext(string ...$extensions): static
    {
        $this->add(__FUNCTION__, function (string $value) use ($extensions): bool {
            $value_ext = pathinfo($value)['extension'];

            foreach ($extensions as $extension) {
                $backward_len = strlen($extension);

                // str_ends_with
                if (0 === substr_compare($value_ext, $extension, - $backward_len)) {
                    return true;
                }
            }

            return false;
        });
        return $this;
    }
}
