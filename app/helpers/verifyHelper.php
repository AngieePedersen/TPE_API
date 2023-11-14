<?php
class VerifyHelpers{

    public static function verifyData($data){
        foreach ($data as $elem) {
            if ((!isset($elem)) || (empty($elem))) {
                if(!is_numeric($elem)){
                    return false;
                }
            }
        }
        return true;
    }

    public static function queryOrder($data){
        return ((isset($data['order'])      || !empty($data['order']))   && 
                (($data['order'] == 'DESC') || ($data['order'] == 'ASC') ||
                 ($data['order'] == 'desc') || ($data['order'] == 'asc')));
    }

    public static function querySort($data,$colums){
        return ((isset($data['sort'])|| !empty($data['sort'])) && (in_array($data['sort'],$colums)));
    }

    public static function queryElem($data,$contElement){
        return ((isset($data['elem'])|| !empty($data['elem'])) && ($data['elem'] <= $contElement) && ($data['elem'] > 0));
    }

    public static function queryLimit($data,$contElement){
        return ((isset($data['limit'])|| !empty($data['limit'])) && ($data['limit'] <=$contElement) && ($data['limit'] > 0));
    }

    public static function queryFilter($data,$colums){
        return ((isset($data['filter'])|| !empty($data['filter'])) && (in_array($data['filter'],$colums)));
    }

    public static function queryValue($data){
        return (isset($data['value'])|| !empty($data['value']));
    }

    public static function queryOperation($data){
        return ((isset($data['operator'])    || !empty($data['operator']))  && 
                (($data['operator'] == '=')  || ($data['operator'] == '>')  ||
                 ($data['operator'] == '<')  || ($data['operator'] == '<=') || 
                 ($data['operator'] == '>=') || ($data['operator'] == '<>') || 
                 ($data['operator'] == 'LIKE')));
    }
}