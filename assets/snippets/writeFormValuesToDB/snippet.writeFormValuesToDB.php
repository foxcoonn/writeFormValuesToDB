<?php
/**
 * Created by PhpStorm.
 * User: Vadim and Serega
 * Date: 14.08.2017
 * Time: 20:21
 */

//<?php
/**
 * Код для подключения:
 * include MODX_BASE_PATH . 'assets/snippets/writeFormValuesToDB/snippet.writeFormValuesToDB.php';
 */

if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}

global $modx;

// Записываем данные в базу
$status = writeToBase($data, $modx->documentIdentifier);
// Устанавливаем флаг об (не)успешной операции
$FormLister->setFormStatus($status);


/** Получает массив с именами и значениями формы и записывает их в базу
 *
 * @param array $formValues ассоциативный массив с данными формы
 * @param $pageId идентификатор страницы с которой производилась отправка
 * @return bool
 */
function writeToBase(array $formValues, $pageId)
{
    global $modx;
    // Идентификатор страницы заносим в отдельную переменную чтоб он попал в колонку со значениями формы
    $formId = $formValues['formid'];
    // После чего удаляем етот елемент массива
    unset($formValues['formid']);
    // Создаем сериализованную строку значений формы для дальнейшей записи в базу
    $formFields = serialize($formValues);
    // Формируем массив для запроса
    $param = array(
        'id' => NULL,
        'id_form' => $formId,
        'id_page' => $pageId,
        'id_user' => getLoginUserID(),
        'value' => $formFields,
        'date' => date("Y-m-d H:i:s")
    );

    $res = $modx->db->insert($param, $modx->getFullTableName('mform_value'));

    return (bool)$res;
}

/** Возвращает идентификатор веб-пользователя или 0
 * @return int
 */
function getLoginUserID()
{
    global $modx;
    return ($modx->getLoginUserID() != '') ? $modx->getLoginUserID() : 0;
}