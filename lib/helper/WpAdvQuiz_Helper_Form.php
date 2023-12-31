<?php

class WpAdvQuiz_Helper_Form
{

    /**
     *
     * @param WpAdvQuiz_Model_Form $form
     * @param mixed $data
     *
     * @return bool
     */
    public static function valid($form, $data)
    {

        if (is_string($data)) {
            $data = trim($data);
        }

        if ($form->isRequired() && empty($data)) {
            return false;
        }

        switch ($form->getType()) {
            case WpAdvQuiz_Model_Form::FORM_TYPE_TEXT:
            case WpAdvQuiz_Model_Form::FORM_TYPE_TEXTAREA:
                return true;
            case WpAdvQuiz_Model_Form::FORM_TYPE_CHECKBOX:
                return empty($data) ? true : $data == '1';
            case WpAdvQuiz_Model_Form::FORM_TYPE_EMAIL:
                return empty($data) ? true : filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
            case WpAdvQuiz_Model_Form::FORM_TYPE_NUMBER:
                return empty($data) ? true : is_numeric($data);
            case WpAdvQuiz_Model_Form::FORM_TYPE_RADIO:
            case WpAdvQuiz_Model_Form::FORM_TYPE_SELECT:
                return empty($data) ? true : in_array($data, $form->getData());
            case WpAdvQuiz_Model_Form::FORM_TYPE_YES_NO:
                return empty($data) ? true : ($data == 0 || $data == 1);
            case WpAdvQuiz_Model_Form::FORM_TYPE_DATE:
                return true;
			//Edit
            case WpAdvQuiz_Model_Form::FORM_TYPE_MULTI_CHECKBOX:
				return true;

        }

        return false;
    }

    /**
     *
     * @param WpAdvQuiz_Model_Form $form
     * @param array $data
     * @return null|string
     */
    public static function validData($form, $data)
    {
        if ($form->isRequired() && empty($data)) {
            return null;
        }

        $check = 0;
        $format = $data['day'] . '-' . $data['month'] . '-' . $data['year'];

        if ($data['day'] > 0 && $data['day'] <= 31) {
            $check++;
        }

        if ($data['month'] > 0 && $data['month'] <= 12) {
            $check++;
        }

        if ($data['year'] >= 1900 && $data['year'] <= date('Y')) {
            $check++;
        }

        if ($form->isRequired()) {
            if ($check == 3) {
                return $format;
            }

            return null;
        }

        if ($check == 0) {
            return '';
        }

        if ($check == 3) {
            return $format;
        }

        return null;
    }

    public static function formToString(WpAdvQuiz_Model_Form $form, $str, $escape = true)
    {
        switch ($form->getType()) {
            case WpAdvQuiz_Model_Form::FORM_TYPE_TEXT:
            case WpAdvQuiz_Model_Form::FORM_TYPE_TEXTAREA:
            case WpAdvQuiz_Model_Form::FORM_TYPE_EMAIL:
            case WpAdvQuiz_Model_Form::FORM_TYPE_NUMBER:
            case WpAdvQuiz_Model_Form::FORM_TYPE_RADIO:
            case WpAdvQuiz_Model_Form::FORM_TYPE_SELECT:
                case WpAdvQuiz_Model_Form::FORM_TYPE_MULTI_CHECKBOX:
                return $escape ? esc_html($str) : $str;
                break;
            case WpAdvQuiz_Model_Form::FORM_TYPE_CHECKBOX:
                return $str == '1' ? __('ticked', 'wp-adv-quiz') : __('not ticked', 'wp-adv-quiz');
                break;
            case WpAdvQuiz_Model_Form::FORM_TYPE_YES_NO:
                return $str == 1 ? __('Yes') : __('No');
                break;
            case WpAdvQuiz_Model_Form::FORM_TYPE_DATE:
                return empty($str) ? '' : date_format(date_create($str), get_option('date_format'));
                break;
        }

        return '';
    }
}
