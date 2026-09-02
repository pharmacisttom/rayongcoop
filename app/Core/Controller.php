<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function render(string $viewPath, array $data = [], string $layout = 'layouts/public'): void
    {
        // Add common data like active user, flash messages, site settings
        $commonData = [
            'currentUser' => auth_user(),
            'flashSuccess' => Session::getFlash('success'),
            'flashError' => Session::getFlash('error'),
            'flashWarning' => Session::getFlash('warning'),
            'flashInfo' => Session::getFlash('info'),
            'request' => $this->request,
        ];

        $html = View::render($viewPath, array_merge($commonData, $data), $layout);
        $this->response->setContent($html)->send();
    }

    protected function json(array $data, int $status = 200): void
    {
        $this->response->json($data, $status);
    }

    protected function redirect(string $url, int $status = 302): void
    {
        $this->response->redirect($url, $status);
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        $this->redirect($referer);
    }

    protected function validate(array $rules, ?array $customData = null): array
    {
        $data = $customData ?? $this->request->all();
        $errors = [];
        $validated = [];

        foreach ($rules as $field => $ruleString) {
            $ruleList = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($ruleList as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }

                switch ($rule) {
                    case 'required':
                        if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                            $errors[$field][] = "กรุณากรอก {$field}";
                        }
                        break;
                    case 'email':
                        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = "รูปแบบอีเมลไม่ถูกต้อง";
                        }
                        break;
                    case 'min':
                        $min = (int) ($params[0] ?? 0);
                        if ($value && strlen((string)$value) < $min) {
                            $errors[$field][] = "ความยาวต้องไม่น้อยกว่า {$min} ตัวอักษร";
                        }
                        break;
                    case 'max':
                        $max = (int) ($params[0] ?? 255);
                        if ($value && strlen((string)$value) > $max) {
                            $errors[$field][] = "ความยาวต้องไม่เกิน {$max} ตัวอักษร";
                        }
                        break;
                    case 'numeric':
                        if ($value !== null && $value !== '' && !is_numeric($value)) {
                            $errors[$field][] = "ต้องเป็นตัวเลขเท่านั้น";
                        }
                        break;
                    case 'in':
                        if ($value && !in_array((string)$value, $params, true)) {
                            $errors[$field][] = "ค่าที่ระบุไม่ถูกต้อง";
                        }
                        break;
                }
            }

            if (!isset($errors[$field])) {
                $validated[$field] = $value;
            }
        }

        if (!empty($errors)) {
            Session::setOldInput($data);
            if ($this->request->isAjax()) {
                $this->json([
                    'success' => false,
                    'message' => 'ข้อมูลที่กรอกไม่ถูกต้อง กรุณาตรวจสอบ',
                    'errors' => $errors,
                ], 422);
            } else {
                Session::flash('error', 'ข้อมูลที่กรอกไม่ถูกต้อง กรุณาตรวจสอบ');
                Session::flash('errors', $errors);
                $this->back();
            }
        }

        return $validated;
    }
}
