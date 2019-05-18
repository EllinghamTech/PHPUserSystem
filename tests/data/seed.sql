-- users
INSERT INTO users VALUES(1,'test_1','$2y$10$UEiQ2/cCTwhbvNu.Teqrse3VUUOjPLO61YnxGJUfyBQ2uq0BTzufK','blackhole~1@ellingham.dev',NULL,1558197712,1,0,0);
INSERT INTO users VALUES(2,'test_2','$2y$10$/kweU2LdDl6DS7gCe8kVUO0amWrSOQniJTb0HB6nZj2N16waFom82','blackhole~2@ellingham.dev',NULL,1558197712,1,0,0);
INSERT INTO users VALUES(3,'test_3','$2y$10$Im2IPlBNoUifZfljU10qju1ySxLtJvOt88/zG266A0syQxCNOZ5je','blackhole~3@ellingham.dev',NULL,1558197712,1,0,0);
INSERT INTO users VALUES(4,'test_4','$2y$10$gFNP2cSXpatn1aL574tibeY.A.jWwl/KjpkIUPNTj1.LUOBaala26','blackhole~4@ellingham.dev',NULL,1558197712,1,0,0);
INSERT INTO users VALUES(5,'test_5','$2y$10$KaCJ/IssRA3bTb09VGJZle6q.uDJEwmtS.mz5qdbQIDFIQ9apnFjG','blackhole~5@ellingham.dev',NULL,1558197712,1,0,0);

-- users_tokens
INSERT INTO users_tokens VALUES('L4O2u+xN7Utv9MOV1uMU+SCGgzq/U6kkYydwUiZ7+MU=',1,'forgot_password',1893456000,0);
INSERT INTO users_tokens VALUES('2ZE666W4qtvZbijz82VKAJiSYRorm821We+elggxwVg=',1,'expired_token',1558190000,0);

-- users_permissions
INSERT INTO users_permissions VALUES(1,'test_permission',1,NULL,NULL);
INSERT INTO users_permissions VALUES(2,'test_permission',31,NULL,NULL);

-- users_limits
INSERT INTO users_limits VALUES(1,'test_limit',1,1000,1546300800,1,'day');
INSERT INTO users_limits VALUES(2,'test_limit',50,100,1546300800,1,'day');

-- users_preferences
INSERT INTO users_preferences VALUES(1,'datetime_format','Y-m-d H:i:s');
INSERT INTO users_preferences VALUES(2,'datetime_format','d/m/Y H:i:s');

-- users_meta
INSERT INTO users_meta VALUES(1,'backup_email',0,'blackhole~6@ellingham.dev',1);
INSERT INTO users_meta VALUES(2,'random',0,'3.14159',0);
INSERT INTO users_meta VALUES(2,'random',1,'1.77245',0);
INSERT INTO users_meta VALUES(2,'random',2,'9.86960',1);

-- users_profiles
INSERT INTO users_profiles VALUES(1,'15ce037e43d2c0','Joey','Joe Bloggs','I am a test user.',NULL);

-- users_sessions
-- No data
