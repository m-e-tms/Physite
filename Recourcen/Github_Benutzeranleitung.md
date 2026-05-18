## GitHub Benutzung:
#### Instalation:
https://git-scm.com/install/

#### Dateien von Github runterladen:
Terminal öffnen: 
win + R 
cmd
Befehle:
cd [gewünster Speicherort]
git clone git clone https://github.com/m-e-tms/Physite.git
cd Physite

#### Neue Branch erstellen:
Befehl:
git checkout -b [Branch Name]

#### Dateien Bearbeiten
Am Projekt weiterarbeiten...
Achtet darauf nur Dateien zu bearbeiten, die zu eurem Thema gehöhren, um merging Fehler zu vermeiden.

#### Branch Pushen ( wieder hochladen)
Befehl:
git add .
git commit -m "[Kommentar zu den Änderungen]"
git push -u origin [Branch Name]
(evtl. muss vor dem Pushen die Email und der Nutzername, mit dem ihr bei Github andgemeldet seid angefordert.)

#### Der neue Branch sollte nun auf Github angezeigt werden
#### Pull Request nur mit Absprache 