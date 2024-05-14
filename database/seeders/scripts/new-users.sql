START TRANSACTION;

DO $$

    DECLARE
        _name VARCHAR := 'Giuseppe Foza';
        _short_name VARCHAR := 'GF';
        _email VARCHAR := 'jofender.foza@gmail.com';
        _password VARCHAR := '$2y$12$/5G1RohU5cOhYocAQc4zP.d9G14wRFFZE8yFARhNS88yLEMKxoT3W';

        _profile_id uuid;
        _user_id uuid;

    BEGIN

        SELECT id INTO _profile_id FROM user_conf.profiles WHERE unique_name = 'ADMIN_MASTER';

        insert into user_conf.users (
            name,
            short_name,
            email,
            password
        ) values (
                     _name,
                     _short_name,
                     _email,
                     _password
                 )
        RETURNING id into _user_id;

        INSERT INTO user_conf.admin_users (user_id) VALUES(_user_id);

        INSERT INTO user_conf.profiles_users (user_id, profile_id) VALUES(_user_id, _profile_id);

END $$;
COMMIT;

START TRANSACTION;

DO $$

    DECLARE
        _name VARCHAR := 'Usuário gerente de projetos';
        _short_name VARCHAR := 'UG';
        _email VARCHAR := 'usuario-gerente-projetos@gmail.com';
        _password VARCHAR := '$2y$12$/5G1RohU5cOhYocAQc4zP.d9G14wRFFZE8yFARhNS88yLEMKxoT3W';

        _profile_id uuid;
        _user_id uuid;

    BEGIN

        SELECT id INTO _profile_id FROM user_conf.profiles WHERE unique_name = 'PROJECT_MANAGER';

        insert into user_conf.users (
            name,
            short_name,
            email,
            password
        ) values (
                     _name,
                     _short_name,
                     _email,
                     _password
                 )
        RETURNING id into _user_id;

        INSERT INTO user_conf.team_users (user_id) VALUES(_user_id);

        INSERT INTO user_conf.profiles_users (user_id, profile_id) VALUES(_user_id, _profile_id);

    END $$;
COMMIT;

DO $$

    DECLARE
        _name VARCHAR := 'Usuário líder de time';
        _short_name VARCHAR := 'UL';
        _email VARCHAR := 'usuario-lider-time@gmail.com';
        _password VARCHAR := '$2y$12$/5G1RohU5cOhYocAQc4zP.d9G14wRFFZE8yFARhNS88yLEMKxoT3W';

        _profile_id uuid;
        _user_id uuid;

    BEGIN

        SELECT id INTO _profile_id FROM user_conf.profiles WHERE unique_name = 'TEAM_LEADER';

        insert into user_conf.users (
            name,
            short_name,
            email,
            password
        ) values (
                     _name,
                     _short_name,
                     _email,
                     _password
                 )
        RETURNING id into _user_id;

        INSERT INTO user_conf.team_users (user_id) VALUES(_user_id);

        INSERT INTO user_conf.profiles_users (user_id, profile_id) VALUES(_user_id, _profile_id);

    END $$;
COMMIT;

DO $$

    DECLARE
        _name VARCHAR := 'Usuário desenvolvedor';
        _short_name VARCHAR := 'UD';
        _email VARCHAR := 'usuario-dev@gmail.com';
        _password VARCHAR := '$2y$12$/5G1RohU5cOhYocAQc4zP.d9G14wRFFZE8yFARhNS88yLEMKxoT3W';

        _profile_id uuid;
        _user_id uuid;

    BEGIN

        SELECT id INTO _profile_id FROM user_conf.profiles WHERE unique_name = 'PROJECT_MEMBER';

        insert into user_conf.users (
            name,
            short_name,
            email,
            password
        ) values (
                     _name,
                     _short_name,
                     _email,
                     _password
                 )
        RETURNING id into _user_id;

        INSERT INTO user_conf.team_users (user_id) VALUES(_user_id);

        INSERT INTO user_conf.profiles_users (user_id, profile_id) VALUES(_user_id, _profile_id);

    END $$;
COMMIT;
