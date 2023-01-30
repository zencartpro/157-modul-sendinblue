#######################################################################################################
# Sendinblue UNINSTALL - 2023-01-29 - webchills
# NUR AUSFÜHREN FALLS SIE DAS MODUL VOLLSTÄNDIG ENTFERNEN WOLLEN!!!
########################################################################################################

DELETE FROM configuration_group WHERE configuration_group_title = 'Sendinblue' LIMIT 1;
DELETE FROM configuration WHERE configuration_key LIKE 'SENDINBLUE_%';
DELETE FROM configuration_language WHERE configuration_key LIKE 'SENDINBLUE_%';
DELETE FROM admin_pages WHERE page_key IN ('configSendinblue');