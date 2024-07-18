CREATE OR REPLACE FUNCTION project.reorder_sections(section_id uuid, new_order INT, section_project_id uuid)
    RETURNS VOID AS $$
DECLARE
    original_order INT;
BEGIN
    SELECT section_order INTO original_order
    FROM project.sections
    WHERE id = section_id;

    UPDATE project.sections
    SET section_order = new_order
    WHERE id = section_id;

    IF original_order < new_order THEN
        UPDATE project.sections
        SET section_order = section_order - 1
        WHERE project_id = section_project_id AND section_order > original_order AND section_order <= new_order AND id != section_id;
    ELSE
        UPDATE project.sections
        SET section_order = section_order + 1
        WHERE project_id = section_project_id AND section_order < original_order AND section_order >= new_order AND id != section_id;
    END IF;
END;
$$ LANGUAGE plpgsql;
