<?php
namespace Riyu\Validation;

trait Validator
{
    public static $data = [];

    public static $minLength = '';

    public static $maxLength = '';

    /**
     * Validate required
     *
     * @param string $value
     * @param string $field
     * @return string|bool
     */
    public static function required($value, $field, $options = null)
    {
        if (empty($value)) {
            return $field . ' is required';
        }

        return false;
    }

    /**
     * Validate email
     *
     * @param string $value
     * @param string $field
     * @return string|bool
     */
    public static function email($value, $field, $options = null)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $field . ' must be a valid email';
        }

        return false;
    }

    /**
     * Validate min length
     *
     * @param string $value
     * @param string $field
     * @param int $length
     * @return string|bool
     */
    public static function min($value, $field, $length)
    {
        if (strlen($value) < $length) {
            self::$minLength = $length;
            return $field . ' must be at least ' . $length . ' characters';
        }

        return false;
    }

    /**
     * Validate max length
     *
     * @param string $value
     * @param string $field
     * @param int $length
     * @return string|bool
     */
    public static function max($value, $field, $length)
    {
        if (strlen($value) > $length) {
            self::$maxLength = $length;
            return $field . ' must be at most ' . $length . ' characters';
        }

        return false;
    }

    /**
     * Validate password
     *
     * @param string $value
     * @param string $field
     * @param string $options
     * @return string|bool
     */
    public static function password($value, $field, $options = null)
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $value)) {
            return $field . ' must contain at least one uppercase letter, one lowercase letter and one number';
        }

        return false;
    }

    /**
     * Validate unique
     *
     * @param string $value
     * @param string $field
     * @param object $model
     * @return string|bool
     */
    public static function unique($value, $field, object $model)
    {
        $model = 'App\\Models\\' . $model;
        $model = new $model;
        $result = $model->where($field, $value)->first();
        if ($result) {
            return $field . ' already exists';
        }

        return false;
    }

    /**
     * Check if value is confirmed
     * 
     * @param mixed $value
     * @param string $field
     * @return string|bool
     */
    public static function confirmed($value, $field, $options = null)
    {
        if ($field != strpos($field, '_confirmation')) {
            $field = $field . '_confirmation';
        }

        if ($value != self::$data[$field]) {
            return $field . ' does not match';
        }

        return false;
    }

    /**
     * Validate numeric
     *
     * @param string $value
     * @param string $field
     * @return string|bool
     */
    public static function numeric($value, $field, $options = null)
    {
        if (!is_numeric($value)) {
            return $field . ' must be numeric';
        }

        return false;
    }

    /**
     * Validate date
     * 
     * format YYYY-MM-DD
     *
     * @param string $value
     * @param string $field
     * @return string|bool
     */
    public static function date($value, $field, $options = null)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $field . ' must be a valid date';
        }

        return false;
    }

    /**
     * Validate year
     * 
     * format YYYY/YYYY
     *
     * @param string $value
     * @param string $field
     * @return string|void
     */
    public static function year($value, $field, $options = null)
    {
        if (!preg_match('/^\d{4}\/\d{4}$/', $value)) {
            return $field . ' must be a valid year';
        }
    }

    /**
     * Validate timestamp
     * 
     * format YYYY-MM-DD HH:MM:SS
     *
     * @param string $value
     * @param string $field
     * @return string|void
     */
    public static function timestamp($value, $field, $options = null)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
            return $field . ' must be a valid timestamp';
        }
    }
}