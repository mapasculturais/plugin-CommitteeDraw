<?php

use MapasCulturais\App;

return [
    'Cria a tabela committee_draw' => function () {
        $app = App::i();
        $conn = $app->em->getConnection();

        // Criar a sequência
        $conn->executeQuery("CREATE SEQUENCE committee_draw_id_seq INCREMENT BY 1 MINVALUE 1 START 1;");

        // Criar a tabela
        $conn->executeQuery("
            CREATE TABLE committee_draw (
                id INT NOT NULL DEFAULT nextval('committee_draw_id_seq'),
                user_id INT NOT NULL,
                evaluation_method_configuration_id INT NOT NULL,
                file_id INT DEFAULT NULL,
                create_timestamp TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                committee_name VARCHAR(255) NOT NULL,
                draw_number INT NOT NULL,
                seed VARCHAR(255) NOT NULL,
                file_md5 VARCHAR(32) NOT NULL,
                number_of_valuers INT NOT NULL,
                input_valuers JSON NOT NULL,
                output_valuers JSON NOT NULL,
                status SMALLINT NOT NULL,
                PRIMARY KEY(id)
            );
        ");

        // Criar índices
        $conn->executeQuery("CREATE INDEX IDX_E7DC8F2CA76ED395 ON committee_draw (user_id);");
        $conn->executeQuery("CREATE INDEX IDX_E7DC8F2C8FC5A771 ON committee_draw (evaluation_method_configuration_id);");
        $conn->executeQuery("CREATE INDEX IDX_E7DC8F2C93CB796C ON committee_draw (file_id);");

        // Adicionar FKs
        $conn->executeQuery("ALTER TABLE committee_draw ADD CONSTRAINT FK_E7DC8F2CA76ED395 FOREIGN KEY (user_id) REFERENCES usr (id) ON DELETE CASCADE;");
        $conn->executeQuery("ALTER TABLE committee_draw ADD CONSTRAINT FK_E7DC8F2C8FC5A771 FOREIGN KEY (evaluation_method_configuration_id) REFERENCES evaluation_method_configuration (id) ON DELETE CASCADE;");
        $conn->executeQuery("ALTER TABLE committee_draw ADD CONSTRAINT FK_E7DC8F2C93CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON DELETE SET NULL;");
    },

];
