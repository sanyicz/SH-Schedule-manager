<b>EN</b><br>
<b>SH Schedule Manager</b><br>
<b>Introduction</b><br>
This program is made to manage the schedules of the students who work for Suli-Host Kft., where I also worked for several years. It is an exercise and was never used in practice.<br>
Every week the project leader creates the schedule for the next week (in the text actual week always means the next week) based on the worker numbers requested by the company and the requests by the workers.<br>
<b>The program's structure</b><br>
<b>Server</b><br>
A program written in Python, the project leader can use this to manage worker data, shifts and company requests, and to create schedules.<br>
The main menu in the uppermost row is for setting time (year and week). At program start it is always the actual (next) week. By setting past values you can view past schedules, by setting future values you can work on them. After setting time you have to click Update to update the program. The Exit button saves the database and closes the program.<br>
On Settings tab you can select languages (Hungarian or English).<br>
On Workers tab you can manage the worker's data. The project leader can add new workers to the database. The password that the worker have to use for setting his requests can be also set here.<br>
On Shifts tab you can add new shifts and activate/deactivate them. A shift has to be active when the company requests at least one workers in the given shift.
On Company requests tab you can set how many workers the company requested for every shift.<br>
On Worker requests tab you can check the requests of the workers. For the actual and future weeks these can be modified, for past week they can only be viewed.
On Schedules tab you can view the requests of all the workers at once. For clearer view, when the cursor hovers over a worker's name, his/her name will turn red in the whole table. By selecting the checkbox you can assign the worker to the given shift. You can create the schedule by clicking Create schedule button. The selected week's schedule can be saved as an Excel file by clicking Export to Excel. Fill schedule mode is under development, it should automatically fill/create the schedule according to the selected algorithm.<br>
On Help tab you can find a description on how to use the program.<br>
<b>Client</b><br>
A web interface, that the workers can use for setting their requests. Communication with the database is handled by PHP code.<br>
Year and week are fix, these always indicate the next week.<br>
The worker has to set his/her requests by selecting his name and the checkboxes next to the shifts fitting for his/her time table. Then the worker has to give the correct password and click Ráérés leadása.<br>
<br>
<br>
<b>HU</b><br>
<b>SH Beosztáskezelő</b><br>
<b>Bevezető</b><br>
Ez a program a Suli-Host Kft-nél (ahol én is több évet töltöttem) dolgozó diákmunkások munkabeosztásának menedzselésére készült. Élesben nem használják, csupán gyakorlásként készítettem.<br>
A munkabeosztás úgy működik, hogy a projektvezető minden héten elkészíti a következő (a szövegben az aktuális hét ezért mindig a következő hetet jelenti) heti beosztást az alapján, hogy a cég milyen műszakokba hány embert kér, valamint a dolgozók által megadott ráérések, kért műszakok alapján.<br>
<b>A program felépítése</b><br>
<b>Szerver</b><br>
Pythonban írt program, amit a projektvezető használhat a dolgozók adatainak, a műszakoknak és a munkarendnek a kezelésére, a beosztások elkészítésére.<br>
A felső sor főmenüjében lehet az időpontot (év, hét) beállítani. A program indulásakor mindig az aktuális (következő) hétnek megfelelő adatok töltődnek be. Múltbeli időpontot beállítva a régebbi beosztások megtekinthetőek, jövőbeli időpontban pedig módosíthatóak. Az időpont beállítása után a Frissít gombbal aktualizálható a program. A Kilépés gombra kattintás menti az adatbázist és bezárja a programot.<br>
A Beállítások fülön lehet nyelvet választani (magyar vagy angol).<br>
A Dolgozók fülön a dolgozók adatait lehet kezelni. A projektvezető itt vehet fel új dolgozót az adatbázisba. Itt lehet megadni azt a jelszót, amit a dolgozó a ráérése leadásánál használni fog.<br>
A Műszakok fülön lehet új műszakot felvenni és műszakok aktivitását állítani. A műszak akkor kell aktív legyen, ha a következő hétre legalább egy dolgozót kért a cég az adott műszakba.<br>
A Munkarend fülön a következő hétre szükséges létszámot lehet beállítani, az aktív műszakoknak megfelelően.<br>
A Ráérések fülön lehet megnézni, hogy egy dolgozó melyik hétre milyen ráérést adott le. Az aktuális hétre módosíthatóak is ráérések, a régebbi időpontokra csak megnézhetőek.<br>
A Beosztás fülön összesítve látszanak az összes dolgozó által megadott ráérések. A jobb áttekinthetőségért, ha egy dolgozó neve fölé visszük a kurzort, a dolgozó neve piros színű lesz. A név melletti jelölőnégyzet kipipálásával oszható be a dolgozó az adott műszakba. A Beosztás elkészítése gomb menti el a beosztást. Az elkészült beosztások Excel táblába is menthetőek. A Beosztás kiegészítése mód fejlesztés alatt van, ezzel automatikusan is létrehozható lesz a beosztás.<br>
A Súgó fülön található egy leírás a program használatához.<br>
<b>Kliens</b><br>
Egy webes felület, amit a dolgozók használhatnak a ráérésük leadására. Az adatbázissal való kommunikációt PHP kód kezeli.<br>
Az év és a hét fix, ezek mindig a következő hetet jelentik.<br>
A dolgozó a neve kiválasztásával és a táblázatban a megfelelő jelölőnégyzetek kipipálásával beállíthatja, később módosíthatja, hogy melyik műszakokban ér rá. A ráérést az adatbázisba menteni csak a helyes jelszó megadásával, a Ráérés leadása gombra kattinva lehet.<br>
