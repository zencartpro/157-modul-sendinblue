# 157-modul-sendinblue
Brevo (Sendinblue) Newsletter Integration für Zen Cart 1.5.7 deutsch

Hinweis: 
Freigegebene getestete Versionen für den Einsatz in Livesystemen ausschließlich unter Releases herunterladen:
* https://github.com/zencartpro/157-modul-sendinblue/releases

Mit diesem Modul wird das Newslettersystem von Brevo (vormals Sendinblue) DSGVO-konform im Shop integriert. 

Kunden, die bei der Neuregistrierung im Shop die Checkbox Newsletter abonnieren ankreuzen, bekommen von Brevo das Double-Optin Bestätigungsmail und werden nach Anclicken des Bestätigungslinks in Ihre Brevo Liste eingetragen.
Übermittelt werden Emailadresse, Vorname und Nachname.

Damit auch Shopbesucher die Möglichkeit haben, den Newsletter zu abonnieren werden drei neue Define Pages mitgeliefert. Auf der Seite index.php?main_page=newsletter kann dann via Tools > Seiteneditor der Formularcode aus der Brevo-Administration eingefügt werden.
Nach Absenden des Formulars kommen die Besucher auf die Seite index.php?main_page=newsletter_confirm mit einem Hinweis auf das nun folgende Double-Optin Bestätigungsmail.
Wird der Bestätigungslink dann angeclickt, kommt zur Bestätigung die Seite index.php?main_page=newsletter_success, der Besucher bleibt also immer auf der Shopseite.

In der Sidebox Information kann ein Link zur Newsletteranmeldung aktiviert werden.

Eine Newsletterabmeldung erfolgt rein über die in den Brevo Mails enthaltenen Abmeldelinks, die gesamte Zen Cart interne Newsletterfunktionalität wird durch Brevo ersetzt.
