<?php

namespace App\Shared\Enums;

enum MessagesEnum: string {
    //General
    case METHOD_NOT_ALLOWED = 'Method not allowed.';
    case RESOURCE_NOT_FOUND = 'Resource not found.';
    case INTERNAL_SERVER_ERROR = 'Internal server error.';
    case UNAUTHORIZED = 'Unauthorized.';
    case NOT_FOUND = 'Not found.';
    case TOO_MANY_REQUESTS = 'Too Many Attempts.';
    case ACCESS_DENIED = 'Acesso negado a este recurso.';
    case INVALID_CODE_TYPE = 'Tipo de código inválido.';
    case UNIQUE_CODE_PREFIX_NOT_FOUND = 'Prefixo não encontrado.';
    case IMPOSSIBLE = 'Não foi possível realizar a sua solicitação.';

    case REGISTER_NOT_FOUND = 'Registro não encontrado.';
    case REGISTER_NOT_ALLOWED = 'Você não tem acesso a este registro.';

    case INVALID_UUID = 'O valor enviado não é um Uuid válido.';
    case INVALID_EMAIL = 'O valor enviado não é um E-mail válido.';
    case INVALID_UNIQUE_NAME = 'O valor enviado não é um nome válido.';
    case NOT_AUTHORIZED = 'Você não tem permissão para acessar este recurso.';
    case MODULE_NOT_AUTHORIZED = 'Você não tem permissão para acessar este módulo.';
    case MUST_BE_AN_ARRAY = 'O campo deve ser um array.';
    case NOT_ENABLED = 'Você não tem permissão para acessar a plataforma. \n Para liberar ou verificar seu acesso entre em contato com o suporte.';

    // Sessions
    case LOGIN_ERROR = 'E-mail ou senha incorretos.';
    case NO_PROFILE = 'Este usuário está vinculado a nenhum perfil do sistema.';
    case INACTIVE_USER = 'Usuário não encontrado ou está inativo no sistema. Se necessário, entre em contato com o suporte.';
    case UNVERIFIED_EMAIL = 'E-mail não verificado.';
    case EMAIL_ALREADY_VERIFIED = 'Este usuário já teve seu e-mail verificado, se necessário entre em contato com o suporte.';
    case SUCCESS_MODIFY_PASSWORD = 'Senha redefinida com sucesso.';
    case INVALID_FORGOT_PASSWORD_CODE = 'Código de verificação expirado, por favor solicite uma nova troca de senha.';
    case INVALID_PROFILE = 'Perfil inválido.';
    case PASSWORD_CODE_NOT_FOUND = 'Código de verificação não encontrado.';

    // Users
    case USER_NOT_FOUND = 'Usuário não encontrado.';
    case CODE_NOT_FOUND = 'Código não encontrado.';
    case INVALID_CODE = 'Código inválido.';
    case PROFILE_NOT_FOUND = 'Perfil não encontrado.';
    case PROFILE_NOT_ALLOWED = 'Sem acesso ao perfil.';
    case EMAIL_ALREADY_EXISTS = 'O E-mail informado já existe no sistema.';
    case PHONE_ALREADY_EXISTS = 'O número de telefone informado já existe no sistema.';
    case INVALID_CURRENT_PASSWORD = 'Senha atual inválida';
    case PERSON_NOT_FOUND = 'Registro Pessoa não encontrado.';
    case USER_WITHOUT_IMAGE = 'Este usuário não possui imagens vinculadas.';


    // City
    case CITY_NOT_FOUND = 'Cidade não encontrada.';
    case INVALID_UF = 'O campo UF é inválido.';

    // Zip Code
    case ADDRESS_NOT_FOUND = 'Não foram encontrados dados com o CEP informado.';

    case REGISTER_NAME_ALREADY_EXISTS = 'Já existe um registro com o nome informado.';
    case PROJECT_NOT_ALLOWED = 'Você não tem acesso a este projeto.';
    case PROJECT_NOT_FOUND = 'Projeto não encontrado.';
    case COLOR_NOT_FOUND = 'Cor não encontrada.';
    case ICON_NOT_FOUND = 'Ícone não encontrado.';
    case TAG_NOT_FOUND = 'Tag não encontrada.';
    case TAG_NOT_BELONGS_TO_PROJECT = 'Esta Tag não está vinculada ao projeto.';
    case TAG_HAS_PROJECTS_IN_DELETE = 'Esta Tag não pode ser excluída pois existem projetos vinculados a ela.';

    case USER_NOT_ALLOWED = 'Você não tem acesso aos dados deste usuário.';
    case PROJECT_TEAM_USER_ALREADY_REMOVED = 'Este usuário não faz parte deste projeto ou já foi removido.';
    case PROJECT_TEAM_USER_ALREADY_EXISTS = 'Este usuário já está vinculado ao projeto.';

    case PROJECT_NOT_ALLOWED_IN_SECTION = 'Você não tem acesso às seções deste projeto.';
    case PROJECT_ID_OR_PROJECT_UNIQUE_NAME_REQUIRED = 'Ao menos um dos parâmetros: projectId ou projectUniqueName deve ser informado.';
    case PROJECT_NOT_ALLOWED_IN_TEAM_USERS = 'Você não tem acesso aos membros deste projeto.';
    case SECTION_NOT_ALLOWED = 'Você não tem acesso a esta seção.';
    case SECTION_NOT_FOUND = 'Seção não encontrada.';
    case TEAM_USER_NOT_BELONGS_TO_PROJECT = 'Este usuário não está vinculado ao projeto.';
    case PROJECT_NOT_ALLOWED_IN_CARD = 'Você não tem acesso aos cards deste projeto.';
    case CARD_NOT_FOUND = 'Card não encontrado.';

}
