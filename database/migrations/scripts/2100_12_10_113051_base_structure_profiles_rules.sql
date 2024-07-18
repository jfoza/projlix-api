DO $$
    DECLARE
        _view_action   varchar := 'VIEW';
        _insert_action varchar := 'INSERT';
        _update_action varchar := 'UPDATE';
        _delete_action varchar := 'DELETE';
        _upload_action varchar := 'UPLOAD';

        -- PROFILE TYPES
        _profile_type1 uuid := uuid_generate_v4();
        _profile_type2 uuid := uuid_generate_v4();

        -- PROFILES
        _profile1 uuid := uuid_generate_v4();
        _profile2 uuid := uuid_generate_v4();
        _profile3 uuid := uuid_generate_v4();
        _profile4 uuid := uuid_generate_v4();

        -- RULES
        _rule1 uuid := uuid_generate_v4();
        _rule2 uuid := uuid_generate_v4();
        _rule3 uuid := uuid_generate_v4();
        _rule4 uuid := uuid_generate_v4();
        _rule5 uuid := uuid_generate_v4();
        _rule6 uuid := uuid_generate_v4();
        _rule7 uuid := uuid_generate_v4();
        _rule8 uuid := uuid_generate_v4();
        _rule9 uuid := uuid_generate_v4();
        _rule10 uuid := uuid_generate_v4();
        _rule11 uuid := uuid_generate_v4();
        _rule12 uuid := uuid_generate_v4();
        _rule13 uuid := uuid_generate_v4();
        _rule14 uuid := uuid_generate_v4();
        _rule15 uuid := uuid_generate_v4();
        _rule16 uuid := uuid_generate_v4();
        _rule17 uuid := uuid_generate_v4();
        _rule18 uuid := uuid_generate_v4();
        _rule19 uuid := uuid_generate_v4();
        _rule20 uuid := uuid_generate_v4();
        _rule21 uuid := uuid_generate_v4();
        _rule22 uuid := uuid_generate_v4();
        _rule23 uuid := uuid_generate_v4();
        _rule24 uuid := uuid_generate_v4();
        _rule25 uuid := uuid_generate_v4();
        _rule26 uuid := uuid_generate_v4();
        _rule27 uuid := uuid_generate_v4();
        _rule28 uuid := uuid_generate_v4();
        _rule29 uuid := uuid_generate_v4();
        _rule30 uuid := uuid_generate_v4();
        _rule31 uuid := uuid_generate_v4();
        _rule32 uuid := uuid_generate_v4();
        _rule33 uuid := uuid_generate_v4();
        _rule34 uuid := uuid_generate_v4();
        _rule35 uuid := uuid_generate_v4();
        _rule36 uuid := uuid_generate_v4();
        _rule37 uuid := uuid_generate_v4();
        _rule38 uuid := uuid_generate_v4();
        _rule39 uuid := uuid_generate_v4();
        _rule40 uuid := uuid_generate_v4();
        _rule41 uuid := uuid_generate_v4();
        _rule42 uuid := uuid_generate_v4();
        _rule43 uuid := uuid_generate_v4();
        _rule44 uuid := uuid_generate_v4();
        _rule45 uuid := uuid_generate_v4();
        _rule46 uuid := uuid_generate_v4();
        _rule47 uuid := uuid_generate_v4();
        _rule48 uuid := uuid_generate_v4();
        _rule49 uuid := uuid_generate_v4();
        _rule50 uuid := uuid_generate_v4();
        _rule51 uuid := uuid_generate_v4();
        _rule52 uuid := uuid_generate_v4();
        _rule53 uuid := uuid_generate_v4();
        _rule54 uuid := uuid_generate_v4();
        _rule55 uuid := uuid_generate_v4();
        _rule56 uuid := uuid_generate_v4();
        _rule57 uuid := uuid_generate_v4();
        _rule58 uuid := uuid_generate_v4();
        _rule59 uuid := uuid_generate_v4();
        _rule60 uuid := uuid_generate_v4();
        _rule61 uuid := uuid_generate_v4();
        _rule62 uuid := uuid_generate_v4();
        _rule63 uuid := uuid_generate_v4();
        _rule64 uuid := uuid_generate_v4();
        _rule65 uuid := uuid_generate_v4();
        _rule66 uuid := uuid_generate_v4();
        _rule67 uuid := uuid_generate_v4();
        _rule68 uuid := uuid_generate_v4();
        _rule69 uuid := uuid_generate_v4();
        _rule70 uuid := uuid_generate_v4();
        _rule71 uuid := uuid_generate_v4();
        _rule72 uuid := uuid_generate_v4();
        _rule73 uuid := uuid_generate_v4();
        _rule74 uuid := uuid_generate_v4();
        _rule75 uuid := uuid_generate_v4();
        _rule76 uuid := uuid_generate_v4();
        _rule77 uuid := uuid_generate_v4();
        _rule78 uuid := uuid_generate_v4();
        _rule79 uuid := uuid_generate_v4();
        _rule80 uuid := uuid_generate_v4();
        _rule81 uuid := uuid_generate_v4();
        _rule82 uuid := uuid_generate_v4();
        _rule83 uuid := uuid_generate_v4();
        _rule84 uuid := uuid_generate_v4();
        _rule85 uuid := uuid_generate_v4();
        _rule86 uuid := uuid_generate_v4();
        _rule87 uuid := uuid_generate_v4();
        _rule88 uuid := uuid_generate_v4();
        _rule89 uuid := uuid_generate_v4();
        _rule90 uuid := uuid_generate_v4();
        _rule91 uuid := uuid_generate_v4();
        _rule92 uuid := uuid_generate_v4();
        _rule93 uuid := uuid_generate_v4();
        _rule94 uuid := uuid_generate_v4();
        _rule95 uuid := uuid_generate_v4();
        _rule96 uuid := uuid_generate_v4();
        _rule97 uuid := uuid_generate_v4();
        _rule98 uuid := uuid_generate_v4();
        _rule99 uuid := uuid_generate_v4();
        _rule100 uuid := uuid_generate_v4();
        _rule101 uuid := uuid_generate_v4();
        _rule102 uuid := uuid_generate_v4();
        _rule103 uuid := uuid_generate_v4();
        _rule104 uuid := uuid_generate_v4();
        _rule105 uuid := uuid_generate_v4();
        _rule106 uuid := uuid_generate_v4();
        _rule107 uuid := uuid_generate_v4();
        _rule108 uuid := uuid_generate_v4();

    BEGIN
        INSERT INTO user_conf.rules (id,description, subject, action)
        VALUES
            (_rule1, 'ROOT', 'ROOT', _view_action),
            (_rule2, 'ADMIN_USERS_VIEW', 'ADMIN_USERS', _view_action),
            (_rule3, 'ADMIN_USERS_INSERT', 'ADMIN_USERS', _insert_action),
            (_rule4, 'ADMIN_USERS_UPDATE', 'ADMIN_USERS', _update_action),
            (_rule5, 'ADMIN_USERS_DELETE', 'ADMIN_USERS', _delete_action),

            (_rule6, 'PROFILES_VIEW', 'PROFILES', _view_action),

            (_rule7, 'CITIES_VIEW', 'CITIES', _view_action ),
            (_rule8, 'STATES_VIEW', 'STATES', _view_action),

            (_rule9, 'PROJECTS_VIEW', 'PROJECTS', _view_action),
            (_rule10, 'PROJECTS_INSERT', 'PROJECTS', _insert_action),
            (_rule11, 'PROJECTS_UPDATE', 'PROJECTS', _update_action),

            (_rule12, 'PROJECTS_ADMIN_MASTER_VIEW', 'PROJECTS_ADMIN_MASTER', _view_action),
            (_rule13, 'PROJECTS_ADMIN_MASTER_INSERT', 'PROJECTS_ADMIN_MASTER', _insert_action),
            (_rule14, 'PROJECTS_ADMIN_MASTER_UPDATE', 'PROJECTS_ADMIN_MASTER', _update_action),
            (_rule15, 'PROJECTS_ADMIN_MASTER_DELETE', 'PROJECTS_ADMIN_MASTER', _delete_action),

            (_rule16, 'PROJECTS_PROJECT_MANAGER_VIEW', 'PROJECTS_PROJECT_MANAGER', _view_action),
            (_rule17, 'PROJECTS_PROJECT_MANAGER_INSERT', 'PROJECTS_PROJECT_MANAGER', _insert_action),
            (_rule18, 'PROJECTS_PROJECT_MANAGER_UPDATE', 'PROJECTS_PROJECT_MANAGER', _update_action),

            (_rule19, 'PROJECTS_TEAM_LEADER_VIEW', 'PROJECTS_TEAM_LEADER', _view_action),
            (_rule20, 'PROJECTS_TEAM_LEADER_INSERT', 'PROJECTS_TEAM_LEADER', _insert_action),
            (_rule21, 'PROJECTS_TEAM_LEADER_UPDATE', 'PROJECTS_TEAM_LEADER', _update_action),

            (_rule22, 'PROJECTS_PROJECT_MEMBER_VIEW', 'PROJECTS_TEAM_LEADER', _view_action),

            (_rule23, 'TEAM_USERS_VIEW', 'TEAM_USERS', _view_action),
            (_rule24, 'TEAM_USERS_INSERT', 'TEAM_USERS', _insert_action),
            (_rule25, 'TEAM_USERS_UPDATE', 'TEAM_USERS', _update_action),

            (_rule26, 'TEAM_USERS_ADMIN_MASTER_VIEW', 'TEAM_USERS_ADMIN_MASTER', _view_action),
            (_rule27, 'TEAM_USERS_ADMIN_MASTER_INSERT', 'TEAM_USERS_ADMIN_MASTER', _insert_action),
            (_rule28, 'TEAM_USERS_ADMIN_MASTER_UPDATE', 'TEAM_USERS_ADMIN_MASTER', _update_action),
            (_rule29, 'TEAM_USERS_ADMIN_MASTER_DELETE', 'TEAM_USERS_ADMIN_MASTER', _delete_action),

            (_rule30, 'TEAM_USERS_PROJECT_MANAGER_VIEW', 'TEAM_USERS_PROJECT_MANAGER', _view_action),
            (_rule31, 'TEAM_USERS_PROJECT_MANAGER_INSERT', 'TEAM_USERS_PROJECT_MANAGER', _insert_action),
            (_rule32, 'TEAM_USERS_PROJECT_MANAGER_UPDATE', 'TEAM_USERS_PROJECT_MANAGER', _update_action),
            (_rule33, 'TEAM_USERS_PROJECT_MANAGER_DELETE', 'TEAM_USERS_PROJECT_MANAGER', _delete_action),

            (_rule34, 'TEAM_USERS_TEAM_LEADER_VIEW', 'TEAM_USERS_TEAM_LEADER', _view_action),
            (_rule35, 'TEAM_USERS_TEAM_LEADER_INSERT', 'TEAM_USERS_TEAM_LEADER', _insert_action),
            (_rule36, 'TEAM_USERS_TEAM_LEADER_UPDATE', 'TEAM_USERS_TEAM_LEADER', _update_action),

            (_rule37, 'TEAM_USERS_PROJECT_MEMBER_VIEW', 'TEAM_USERS_PROJECT_MEMBER', _view_action),

            (_rule38, 'NOTES_VIEW', 'NOTES', _view_action),
            (_rule39, 'NOTES_INSERT', 'NOTES', _insert_action),
            (_rule40, 'NOTES_UPDATE', 'NOTES', _update_action),
            (_rule41, 'NOTES_DELETE', 'NOTES', _delete_action),

            (_rule42, 'COLORS_VIEW', 'COLORS', _view_action),
            (_rule43, 'ICONS_VIEW', 'ICONS', _view_action),

            (_rule44, 'SECTIONS_VIEW', 'SECTIONS', _view_action),
            (_rule45, 'SECTIONS_INSERT', 'SECTIONS', _insert_action),
            (_rule46, 'SECTIONS_UPDATE', 'SECTIONS', _update_action),

            (_rule47, 'SECTIONS_ADMIN_MASTER_VIEW', 'SECTIONS_ADMIN_MASTER',   _view_action),
            (_rule48, 'SECTIONS_ADMIN_MASTER_INSERT', 'SECTIONS_ADMIN_MASTER', _insert_action),
            (_rule49, 'SECTIONS_ADMIN_MASTER_UPDATE', 'SECTIONS_ADMIN_MASTER', _update_action),
            (_rule50, 'SECTIONS_ADMIN_MASTER_DELETE', 'SECTIONS_ADMIN_MASTER', _delete_action),

            (_rule51, 'SECTIONS_PROJECT_MANAGER_VIEW', 'SECTIONS_PROJECT_MANAGER', _view_action),
            (_rule52, 'SECTIONS_PROJECT_MANAGER_INSERT', 'SECTIONS_PROJECT_MANAGER', _insert_action),
            (_rule53, 'SECTIONS_PROJECT_MANAGER_UPDATE', 'SECTIONS_PROJECT_MANAGER', _update_action),
            (_rule54, 'SECTIONS_PROJECT_MANAGER_DELETE', 'SECTIONS_PROJECT_MANAGER', _delete_action),

            (_rule55, 'SECTIONS_TEAM_LEADER_VIEW', 'SECTIONS_TEAM_LEADER', _view_action),
            (_rule56, 'SECTIONS_TEAM_LEADER_INSERT', 'SECTIONS_TEAM_LEADER', _insert_action),
            (_rule57, 'SECTIONS_TEAM_LEADER_UPDATE', 'SECTIONS_TEAM_LEADER', _update_action),
            (_rule58, 'SECTIONS_TEAM_LEADER_DELETE', 'SECTIONS_TEAM_LEADER', _delete_action),

            (_rule59, 'SECTIONS_PROJECT_MEMBER_VIEW', 'SECTIONS_PROJECT_MEMBER', _view_action),

            (_rule60, 'TAGS_VIEW', 'TAGS', _view_action),
            (_rule61, 'TAGS_INSERT', 'TAGS', _insert_action),
            (_rule62, 'TAGS_UPDATE', 'TAGS', _update_action),
            (_rule63, 'TAGS_DELETE', 'TAGS', _delete_action),

            (_rule64, 'CARDS_VIEW', 'CARDS', _view_action),
            (_rule65, 'CARDS_INSERT', 'CARDS', _insert_action),
            (_rule66, 'CARDS_UPDATE', 'CARDS', _update_action),

            (_rule67, 'CARDS_ADMIN_MASTER_VIEW',      'CARDS_ADMIN_MASTER',   _view_action),
            (_rule68, 'CARDS_ADMIN_MASTER_INSERT',    'CARDS_ADMIN_MASTER', _insert_action),
            (_rule69, 'CARDS_ADMIN_MASTER_UPDATE',    'CARDS_ADMIN_MASTER', _update_action),
            (_rule70, 'CARDS_ADMIN_MASTER_DELETE',    'CARDS_ADMIN_MASTER', _delete_action),

            (_rule71, 'CARDS_PROJECT_MANAGER_VIEW',   'CARDS_PROJECT_MANAGER', _view_action),
            (_rule72, 'CARDS_PROJECT_MANAGER_INSERT', 'CARDS_PROJECT_MANAGER', _insert_action),
            (_rule73, 'CARDS_PROJECT_MANAGER_UPDATE', 'CARDS_PROJECT_MANAGER', _update_action),
            (_rule74, 'CARDS_PROJECT_MANAGER_DELETE', 'CARDS_PROJECT_MANAGER', _delete_action),

            (_rule75, 'CARDS_TEAM_LEADER_VIEW',       'CARDS_TEAM_LEADER', _view_action),
            (_rule76, 'CARDS_TEAM_LEADER_INSERT',     'CARDS_TEAM_LEADER', _insert_action),
            (_rule77, 'CARDS_TEAM_LEADER_UPDATE',     'CARDS_TEAM_LEADER', _update_action),
            (_rule78, 'CARDS_TEAM_LEADER_DELETE',     'CARDS_TEAM_LEADER', _delete_action),

            (_rule79, 'CARDS_PROJECT_MEMBER_VIEW',    'CARDS_PROJECT_MEMBER', _view_action),
            (_rule80, 'CARDS_PROJECT_MEMBER_INSERT',  'CARDS_PROJECT_MEMBER', _insert_action),
            (_rule81, 'CARDS_PROJECT_MEMBER_UPDATE',  'CARDS_PROJECT_MEMBER', _update_action),
            (_rule82, 'CARDS_PROJECT_MEMBER_DELETE',  'CARDS_PROJECT_MEMBER', _delete_action),

            (_rule83, 'PROFILES_ADMIN_MASTER_VIEW', 'PROFILES_ADMIN_MASTER', _view_action),
            (_rule84, 'PROFILES_PROJECT_MANAGER_VIEW', 'PROFILES_PROJECT_MANAGER', _view_action),
            (_rule85, 'PROFILES_TEAM_LEADER_VIEW', 'PROFILES_TEAM_LEADER', _view_action),
            (_rule86, 'PROFILES_PROJECT_MEMBER_VIEW', 'PROFILES_PROJECT_MEMBER', _view_action),

            (_rule87, 'PROJECTS_ADMIN_MASTER_TEAM_USER_INSERT', 'PROJECTS_ADMIN_MASTER_TEAM_USER', _insert_action),
            (_rule88, 'PROJECTS_PROJECT_MANAGER_TEAM_USER_INSERT', 'PROJECTS_PROJECT_MANAGER_TEAM_USER', _insert_action),
            (_rule89, 'PROJECTS_TEAM_LEADER_TEAM_USER_INSERT', 'PROJECTS_TEAM_LEADER_TEAM_USER', _insert_action),

            (_rule90, 'PROJECTS_ADMIN_MASTER_TEAM_USER_DELETE', 'PROJECTS_ADMIN_MASTER_TEAM_USER', _delete_action),
            (_rule91, 'PROJECTS_PROJECT_MANAGER_TEAM_USER_DELETE', 'PROJECTS_PROJECT_MANAGER_TEAM_USER', _delete_action),
            (_rule92, 'PROJECTS_TEAM_LEADER_TEAM_USER_DELETE', 'PROJECTS_TEAM_LEADER_TEAM_USER', _delete_action),

            (_rule93, 'PROJECTS_ADMIN_MASTER_TAGS_INSERT',    'PROJECTS_ADMIN_MASTER_TAGS', _insert_action),
            (_rule94, 'PROJECTS_PROJECT_MANAGER_TAGS_INSERT', 'PROJECTS_PROJECT_MANAGER_TAGS', _insert_action),
            (_rule95, 'PROJECTS_TEAM_LEADER_TAGS_INSERT',     'PROJECTS_TEAM_LEADER_TAGS', _insert_action),

            (_rule96, 'PROJECTS_ADMIN_MASTER_TAGS_DELETE',    'PROJECTS_ADMIN_MASTER_TAGS', _delete_action),
            (_rule97, 'PROJECTS_PROJECT_MANAGER_TAGS_DELETE', 'PROJECTS_PROJECT_MANAGER_TAGS', _delete_action),
            (_rule98, 'PROJECTS_TEAM_LEADER_TAGS_DELETE',     'PROJECTS_TEAM_LEADER_TAGS', _delete_action),

            (_rule99, 'PROJECTS_ADMIN_MASTER_ICON_UPDATE',     'PROJECTS_ADMIN_MASTER_ICON', _update_action),
            (_rule100, 'PROJECTS_PROJECT_MANAGER_ICON_UPDATE', 'PROJECTS_PROJECT_MANAGER_ICON', _update_action),
            (_rule101, 'PROJECTS_TEAM_LEADER_ICON_UPDATE',     'PROJECTS_TEAM_LEADER_ICON', _update_action),

            (_rule102, 'PROJECTS_ADMIN_MASTER_INFO_UPDATE',    'PROJECTS_ADMIN_MASTER_INFO', _update_action),
            (_rule103, 'PROJECTS_PROJECT_MANAGER_INFO_UPDATE', 'PROJECTS_PROJECT_MANAGER_INFO', _update_action),
            (_rule104, 'PROJECTS_TEAM_LEADER_INFO_UPDATE',     'PROJECTS_TEAM_LEADER_INFO', _update_action),

            (_rule105, 'SECTIONS_ORDER_ADMIN_MASTER_UPDATE',    'SECTIONS_ORDER_ADMIN_MASTER', _update_action),
            (_rule106, 'SECTIONS_ORDER_PROJECT_MANAGER_UPDATE', 'SECTIONS_ORDER_PROJECT_MANAGER', _update_action),
            (_rule107, 'SECTIONS_ORDER_TEAM_LEADER_UPDATE',     'SECTIONS_ORDER_TEAM_LEADER', _update_action),
            (_rule108, 'SECTIONS_ORDER_PROJECT_MEMBER_UPDATE',  'SECTIONS_ORDER_PROJECT_MEMBER', _update_action)
        ;

        INSERT INTO user_conf.profile_types (id, description, unique_name)
        VALUES
            (
                _profile_type1,
                'Administrativo',
                'ADMINISTRATIVE'
            ),
            (
                _profile_type2,
                'Operacional',
                'OPERATIONAL'
            );

        INSERT INTO user_conf.profiles (id, profile_type_id, description, unique_name)
        VALUES
            (
                _profile1,
                _profile_type1,
                'Admin Master',
                'ADMIN_MASTER'
            ),
            (
                _profile2,
                _profile_type2,
                'Gerente de Projetos',
                'PROJECT_MANAGER'
            ),
            (
                _profile3,
                _profile_type2,
                'Líder de Equipe',
                'TEAM_LEADER'
            ),
            (
                _profile4,
                _profile_type2,
                'Membro de Equipe/Projeto',
                'PROJECT_MEMBER'
            );

        INSERT INTO user_conf.profiles_rules (profile_id, rule_id)
        VALUES
            -- ADMIN MASTER
            (_profile1, _rule1),
            (_profile1, _rule2),
            (_profile1, _rule3),
            (_profile1, _rule4),
            (_profile1, _rule5),
            (_profile1, _rule6),
            (_profile1, _rule7),
            (_profile1, _rule8),
            (_profile1, _rule9),
            (_profile1, _rule10),
            (_profile1, _rule11),
            (_profile1, _rule12),
            (_profile1, _rule13),
            (_profile1, _rule14),
            (_profile1, _rule15),
            (_profile1, _rule23),
            (_profile1, _rule24),
            (_profile1, _rule25),
            (_profile1, _rule26),
            (_profile1, _rule27),
            (_profile1, _rule28),
            (_profile1, _rule29),
            (_profile1, _rule38),
            (_profile1, _rule39),
            (_profile1, _rule40),
            (_profile1, _rule41),
            (_profile1, _rule42),
            (_profile1, _rule43),
            (_profile1, _rule44),
            (_profile1, _rule45),
            (_profile1, _rule46),
            (_profile1, _rule47),
            (_profile1, _rule48),
            (_profile1, _rule49),
            (_profile1, _rule50),
            (_profile1, _rule60),
            (_profile1, _rule61),
            (_profile1, _rule62),
            (_profile1, _rule63),
            (_profile1, _rule64),
            (_profile1, _rule65),
            (_profile1, _rule66),
            (_profile1, _rule67),
            (_profile1, _rule68),
            (_profile1, _rule69),
            (_profile1, _rule70),
            (_profile1, _rule83),
            (_profile1, _rule87),
            (_profile1, _rule90),
            (_profile1, _rule93),
            (_profile1, _rule96),
            (_profile1, _rule99),
            (_profile1, _rule102),
            (_profile1, _rule105),

            -- PROJECT_MANAGER
            (_profile2, _rule1),
            (_profile2, _rule6),
            (_profile2, _rule7),
            (_profile2, _rule8),
            (_profile2, _rule9),
            (_profile2, _rule10),
            (_profile2, _rule11),
            (_profile2, _rule16),
            (_profile2, _rule17),
            (_profile2, _rule18),
            (_profile2, _rule23),
            (_profile2, _rule24),
            (_profile2, _rule25),
            (_profile2, _rule30),
            (_profile2, _rule31),
            (_profile2, _rule32),
            (_profile2, _rule33),
            (_profile2, _rule38),
            (_profile2, _rule39),
            (_profile2, _rule40),
            (_profile2, _rule41),
            (_profile2, _rule42),
            (_profile2, _rule43),
            (_profile2, _rule44),
            (_profile2, _rule45),
            (_profile2, _rule46),
            (_profile2, _rule51),
            (_profile2, _rule52),
            (_profile2, _rule53),
            (_profile2, _rule54),
            (_profile2, _rule60),
            (_profile2, _rule61),
            (_profile2, _rule62),
            (_profile2, _rule63),
            (_profile2, _rule64),
            (_profile2, _rule65),
            (_profile2, _rule66),
            (_profile2, _rule71),
            (_profile2, _rule72),
            (_profile2, _rule73),
            (_profile2, _rule74),
            (_profile2, _rule84),
            (_profile2, _rule88),
            (_profile2, _rule91),
            (_profile2, _rule94),
            (_profile2, _rule97),
            (_profile2, _rule100),
            (_profile2, _rule103),
            (_profile2, _rule106),

            -- TEAM_LEADER
            (_profile3, _rule1),
            (_profile3, _rule6),
            (_profile3, _rule7),
            (_profile3, _rule8),
            (_profile3, _rule9),
            (_profile3, _rule10),
            (_profile3, _rule11),
            (_profile3, _rule19),
            (_profile3, _rule20),
            (_profile3, _rule21),
            (_profile3, _rule23),
            (_profile3, _rule24),
            (_profile3, _rule25),
            (_profile3, _rule34),
            (_profile3, _rule35),
            (_profile3, _rule36),
            (_profile3, _rule38),
            (_profile3, _rule39),
            (_profile3, _rule40),
            (_profile3, _rule41),
            (_profile3, _rule42),
            (_profile3, _rule43),
            (_profile3, _rule44),
            (_profile3, _rule45),
            (_profile3, _rule46),
            (_profile3, _rule55),
            (_profile3, _rule56),
            (_profile3, _rule57),
            (_profile3, _rule58),
            (_profile3, _rule60),
            (_profile3, _rule61),
            (_profile3, _rule62),
            (_profile3, _rule63),
            (_profile3, _rule64),
            (_profile3, _rule65),
            (_profile3, _rule66),
            (_profile3, _rule75),
            (_profile3, _rule76),
            (_profile3, _rule77),
            (_profile3, _rule78),
            (_profile3, _rule85),
            (_profile3, _rule89),
            (_profile3, _rule92),
            (_profile3, _rule95),
            (_profile3, _rule98),
            (_profile3, _rule101),
            (_profile3, _rule104),
            (_profile3, _rule107),

            -- PROJECT_MEMBER
            (_profile4, _rule1),
            (_profile4, _rule6),
            (_profile4, _rule7),
            (_profile4, _rule8),
            (_profile4, _rule9),
            (_profile4, _rule22),
            (_profile4, _rule37),
            (_profile4, _rule38),
            (_profile4, _rule49),
            (_profile4, _rule40),
            (_profile4, _rule41),
            (_profile4, _rule42),
            (_profile4, _rule43),
            (_profile4, _rule59),
            (_profile4, _rule79),
            (_profile4, _rule80),
            (_profile4, _rule81),
            (_profile4, _rule82),
            (_profile4, _rule86),
            (_profile4, _rule108)

        ;
    END $$;
