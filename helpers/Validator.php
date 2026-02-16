<?php

class Validator {
    private $errors = [];

    public function required($field, $value, $label = null) {
        if (empty(trim($value))) {
            $label = $label ?: $field;
            $this->errors[$field] = "$label es obligatorio.";
        }
        return $this;
    }

    public function email($field, $value, $label = null) {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $label = $label ?: $field;
            $this->errors[$field] = "$label no es válido.";
        }
        return $this;
    }

    public function minLength($field, $value, $min, $label = null) {
        if (!empty($value) && strlen($value) < $min) {
            $label = $label ?: $field;
            $this->errors[$field] = "$label debe tener al menos $min caracteres.";
        }
        return $this;
    }

    public function maxLength($field, $value, $max, $label = null) {
        if (!empty($value) && strlen($value) > $max) {
            $label = $label ?: $field;
            $this->errors[$field] = "$label no puede exceder $max caracteres.";
        }
        return $this;
    }

    public function matches($field, $value1, $value2, $label = null) {
        if ($value1 !== $value2) {
            $label = $label ?: $field;
            $this->errors[$field] = "$label no coincide.";
        }
        return $this;
    }

    public function dni($field, $value, $label = null) {
        if (!empty($value)) {
            $value = preg_replace('/\D/', '', $value);
            if (!preg_match('/^\d{8}$/', $value)) {
                $label = $label ?: $field;
                $this->errors[$field] = "$label debe tener 8 dígitos.";
            }
        }
        return $this;
    }

    public function phone($field, $value, $label = null) {
        if (!empty($value)) {
            $clean = preg_replace('/\D/', '', $value);
            if (!preg_match('/^9\d{8}$/', $clean)) {
                $label = $label ?: $field;
                $this->errors[$field] = "$label debe ser un número celular válido (9 dígitos, empieza con 9).";
            }
        }
        return $this;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getError($field) {
        return $this->errors[$field] ?? '';
    }
}
?>