<?php

$lang['required']					= "O campo {field} é obrigatório.";
$lang['differs']					= "O campo {field} tem que ser diferente do campo {param}.";
$lang['isset']						= "O campo {field} deve ter um valor definido.";
$lang['valid_email']				= "O campo {field} deve conter um endereço de e-mail válido.";
$lang['valid_emails']				= "O campo {field} deve apenas endereços de e-mail válidos.";
$lang['valid_url']					= "O campo {field} deve conter uma URL válida.";
$lang['valid_url_strict']			= "O campo {field} deve conter uma URL válida.";
$lang['valid_date']					= "O campo {field} deve ser uma data válida.";
$lang['valid_ip']					= "O campo {field} deve conter um IP válido.";
$lang['min_length']					= "O campo {field} deve conter pelo menos {param} caracteres.";
$lang['max_length']					= "O campo {field} não deve conter mais de {param} caracteres.";
$lang['exact_length']				= "O campo {field} deve conter exatamente {param} caracteres.";
$lang['alpha']						= "O campo {field} deve conter apenas letras.";
$lang['alpha_space']				= "O campo {field} deve conter apenas letras e espaço.";
$lang['alpha_numeric']				= "O campo {field} deve conter apenas caracteres alfanuméricos.";
$lang['alpha_dash']					= "O campo {field} deve conter apenas caracteres alfanuméricos, sublinhados e traços.";
$lang['numeric']					= "O campo {field} deve conter apenas números.";
$lang['is_numeric']					= "O campo {field} deve conter apenas caracteres numéricos.";
$lang['integer']					= "O campo {field} deve conter um número inteiro.";
$lang['regex_match']				= "O campo {field} não está no formato correto.";
$lang['matches']					= "O campo {field} não é igual ao anterior.";
$lang['is_unique'] 					= "O valor do campo {field} já está sendo utilizado.";
$lang['is_natural']					= "O campo {field} deve conter apenas números positivos.";
$lang['is_natural_no_zero']			= "O campo {field} deve conter apenas números maiores que zero.";
$lang['decimal']					= "O campo {field} deve conter um número decimal.";
$lang['hex']						= "O campo {field} deve ser um valor hexadecimal.";
$lang['if_exist']					= "O campo {field} deve existir.";
$lang['is_not_unique']				= "O valor do campo {field} não existe.";
$lang['in_list']					= "O valor do campo {field} deve estar nesta lista: {param}.";
$lang['not_in_list']				= "O valor do campo {field} não deve estar nesta lista: {param}.";
$lang['less_than']					= "O campo {field} deve conter um número menor que {param}.";
$lang['less_than_equal_to']			= "O campo {field} deve conter um número menor ou igual a {param}.";
$lang['greater_than']				= "O campo {field} deve conter um número maior que {param}.";
$lang['greater_than_equal_to']	    = "O campo {field} deve conter um número maior ou igual a {param}.";
$lang['alpha_numeric_space']		= "O campo {field} aceita apenas letras, números e espaço. Sem acentuação.";
$lang['alpha_numeric_punct']		= "O campo {field} aceita apenas letras, números, espaço e caracteres especiais. Sem acentuação.";
$lang['string']						= "O campo {field} deve ser uma string.";
$lang['timezone']					= "O campo {field} deve ser um fuso horário.";
$lang['valid_base64']				= "O campo {field} deve ser um base64 válido.";
$lang['valid_json']					= "O campo {field} deve ser um json válido.";

$lang['uploaded']					= "O campo {field} deve ser informado.";
$lang['max_size']					= "O campo {field} deve ser menor do que {param}kb.";
$lang['max_dims']					= "O campo {field} deve ser menor do que {param}px.";
$lang['mime_in']					= "O campo {field} deve ser do(s) tipo(s) {param}.";
$lang['ext_in']						= "O campo {field} deve ser do(s) tipo(s) {param}.";
$lang['is_image']					= "O campo {field} deve ser uma imagem.";

// override core en language system validation or define your own en language validation message
return $lang;
