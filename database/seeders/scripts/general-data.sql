START TRANSACTION;

DO $$
    DECLARE
        _project1 uuid := uuid_generate_v4();
        _project2 uuid := uuid_generate_v4();

        _team_user1 uuid;
        _team_user2 uuid;

        _color_id uuid;
        _icon_id uuid;

        _tag1 uuid := uuid_generate_v4();
        _tag2 uuid := uuid_generate_v4();
        _tag3 uuid := uuid_generate_v4();
        _tag4 uuid := uuid_generate_v4();
        _tag5 uuid := uuid_generate_v4();
    BEGIN
        INSERT INTO general.colors(hexadecimal)
        VALUES ('#993399'), ('#9b111'), ('#96E035');

        INSERT INTO general.icons(type, name)
        VALUES ('FONT_AWESOME', 'fa-solid fa-filter'), ('FONT_AWESOME', 'fa-solid fa-folder-open'), ('FONT_AWESOME', 'fa-solid fa-user-secret');

        INSERT INTO general.tags(id, name)
        VALUES (_tag1, 'Melhoria de Sistema'),
               (_tag2, 'Incidente'),
               (_tag3, 'Projetos'),
               (_tag4, 'Incidente Massivo'),
               (_tag5, 'Não cobrado');

        SELECT tu.id INTO _team_user1 FROM user_conf.team_users tu
                                               JOIN user_conf.users u ON tu.user_id = u.id
        WHERE u.email = 'usuario-lider-time@gmail.com';

        SELECT tu.id INTO _team_user2 FROM user_conf.team_users tu
                                               JOIN user_conf.users u ON tu.user_id = u.id
        WHERE u.email = 'usuario-dev@gmail.com';

        INSERT INTO project.projects(id, name, description)
        VALUES (_project1, 'Projlix', 'Lorem ipsum dolor sit amet, consect etur adip iscing elit. Proin rhoncus urn a dictum neque molestie ultricies.'),
               (_project2, 'Aposturch', 'Lorem ipsum dolor sit amet, consect etur adip iscing elit. Proin rhoncus urn a dictum neque molestie ultricies.');

        INSERT INTO user_conf.projects_team_users(team_user_id, project_id)
        VALUES
            (_team_user1, _project1),
            (_team_user2, _project1),
            (_team_user1, _project2),
            (_team_user2, _project2);

        SELECT id INTO _color_id FROM general.colors LIMIT 1;
        SELECT id INTO _icon_id FROM general.icons LIMIT 1;

        INSERT INTO project.sections(project_id, color_id, icon_id, name)
        VALUES (_project1, _color_id, _icon_id, 'Backlog'),
               (_project1, _color_id, _icon_id, 'Em Desenvolvimento'),
               (_project1, _color_id, _icon_id, 'Auditoria');

        INSERT INTO project.projects_tags(project_id, tag_id)
        VALUES (_project1, _tag1),
               (_project1, _tag2),
               (_project1, _tag3),
               (_project1, _tag4),
               (_project1, _tag5);
    END $$;
COMMIT;
