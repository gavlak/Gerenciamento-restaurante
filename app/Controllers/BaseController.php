<?php
declare(strict_types=1);

namespace App\Controllers;

abstract class BaseController
{
    /**
     * Renderiza uma view dentro do layout (header + view + footer).
     * As chaves de $data se tornam variáveis disponíveis na view.
     */
    protected function render(string $view, array $data = []): void
    {
        $data['errors'] ??= [];
        $data['old']    ??= [];

        extract($data, EXTR_SKIP);

        require ROOT_PATH . '/views/layout/header.php';
        require ROOT_PATH . '/views/' . $view . '.php';
        require ROOT_PATH . '/views/layout/footer.php';
    }

    /**
     * Redireciona para um caminho (BASE_URL é prefixado automaticamente).
     */
    protected function redirect(string $path): never
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * Validação server-side.
     * Regras disponíveis: required, min:N, max:N, numeric, email
     *
     * @return array<string, string> ['campo' => 'mensagem de erro']
     */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = trim((string) ($data[$field] ?? ''));
            $ruleList = explode('|', $ruleString);

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && $value === '') {
                    $errors[$field] = ucfirst($field) . ' é obrigatório.';
                    break;
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = ucfirst($field) . " deve ter pelo menos {$min} caracteres.";
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field] = ucfirst($field) . " não pode ter mais de {$max} caracteres.";
                    }
                }

                if ($rule === 'numeric' && $value !== '' && !is_numeric($value)) {
                    $errors[$field] = ucfirst($field) . ' deve ser um número.';
                }

                if ($rule === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = ucfirst($field) . ' deve ser um e-mail válido.';
                }
            }
        }

        return $errors;
    }
}
