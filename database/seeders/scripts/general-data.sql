START TRANSACTION;

DO $$
    DECLARE
        _project1 uuid := uuid_generate_v4();
        _project2 uuid := uuid_generate_v4();

        _team_user1 uuid;
        _team_user2 uuid;

        _tag_color_id1 uuid;
        _tag_color_id2 uuid;
        _tag_color_id3 uuid;
        _tag_color_id4 uuid;
        _tag_color_id5 uuid;

        _project_icon_id1 uuid;
        _project_icon_id2 uuid;

        _tag1 uuid := uuid_generate_v4();
        _tag2 uuid := uuid_generate_v4();
        _tag3 uuid := uuid_generate_v4();
        _tag4 uuid := uuid_generate_v4();
        _tag5 uuid := uuid_generate_v4();
    BEGIN
        SELECT id INTO _tag_color_id1 FROM general.colors WHERE hexadecimal = '#00aaff';
        SELECT id INTO _tag_color_id2 FROM general.colors WHERE hexadecimal = '#d01f2e';
        SELECT id INTO _tag_color_id3 FROM general.colors WHERE hexadecimal = '#a179f2';
        SELECT id INTO _tag_color_id4 FROM general.colors WHERE hexadecimal = '#d01f2e';
        SELECT id INTO _tag_color_id5 FROM general.colors WHERE hexadecimal = '#96db0b';

        SELECT id INTO _project_icon_id1 FROM general.icons WHERE name = 'ActivityIcon';
        SELECT id INTO _project_icon_id2 FROM general.icons WHERE name = 'AnchorIcon';

        INSERT INTO general.tags(id, color_id, name)
        VALUES (_tag1, _tag_color_id1, 'Melhoria de Sistema'),
               (_tag2, _tag_color_id2, 'Incidente'),
               (_tag3, _tag_color_id3, 'Projetos'),
               (_tag4, _tag_color_id4, 'Incidente Massivo'),
               (_tag5, _tag_color_id5, 'Não cobrado');

        SELECT tu.id INTO _team_user1 FROM user_conf.team_users tu
        JOIN user_conf.users u ON tu.user_id = u.id
        WHERE u.email = 'usuario-lider-time@gmail.com';

        SELECT tu.id INTO _team_user2 FROM user_conf.team_users tu
        JOIN user_conf.users u ON tu.user_id = u.id
        WHERE u.email = 'usuario-dev@gmail.com';

        INSERT INTO project.projects(id, icon_id, name, unique_name, description)
        VALUES (_project1, _project_icon_id1, 'Projlix', 'projlix', 'Lorem ipsum dolor sit amet, consect etur adip iscing elit. Proin rhoncus urn a dictum neque molestie ultricies.'),
               (_project2, _project_icon_id2, 'Aposturch', 'aposturch', 'Lorem ipsum dolor sit amet, consect etur adip iscing elit. Proin rhoncus urn a dictum neque molestie ultricies.');

        INSERT INTO user_conf.projects_team_users(team_user_id, project_id)
        VALUES
            (_team_user1, _project1),
            (_team_user2, _project1),
            (_team_user1, _project2),
            (_team_user2, _project2);

        INSERT INTO project.sections(project_id, color_id, icon_id, name)
        VALUES (_project1, _tag_color_id1, _project_icon_id1, 'Backlog'),
               (_project1, _tag_color_id2, _project_icon_id2, 'Em Desenvolvimento'),
               (_project1, _tag_color_id3, _project_icon_id1, 'Auditoria');

        INSERT INTO project.projects_tags(project_id, tag_id)
        VALUES (_project1, _tag1),
               (_project1, _tag2),
               (_project1, _tag3),
               (_project1, _tag4),
               (_project1, _tag5);
    END $$;
COMMIT;
