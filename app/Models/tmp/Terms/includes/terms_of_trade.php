
<?php

$company = Company::get();


$owner      = $company["owner"];
$company_name    = $company["company"];
$cvr        = $company["cvr"];
$address    = $company["address"];
$zipcode    = $company["zipcode"];
$city       = $company["city"];

$phone      = current($company["phone"]);
$email      = current($company["email"]);



$terms =
"
Handelsbetingelser til ".$company_name."

".$company_name ."
CVR: ".$cvr."
".$address."
".$zipcode." ".$city."
Tlf: ".$phone["number"]."
E-mail: ".$email["email"]."


Personlige data:
Dine privatoplysninger, som du angiver under en bestilling, bliver gemt i ".$company_name."'s databasen. Oplysningerne bruges udelukkende til at bestemme modtageren af de købte varer, og videregives ikke uden brugerens samtykke. Kortbetaling foregår sikkert og krypteret igennem vores indløser YourPay Aps.
Har du spørgsmål til håndtering af persondata, bedes du kontakte os.

Cookies
Websitet anvender ”cookies”, der er en tekstfil, som gemmes på din computer, mobil el. tilsvarende med det formål at genkende den, huske indstillinger, udføre statistik og målrette annoncer. Cookies kan ikke indeholde skadelig kode som f.eks. virus.

Det er muligt at slette eller blokere for cookies. Se vejledning: http://minecookies.org/cookiehandtering


Priser 
Hos ".$company_name." tager vi forbehold for eventuelle fejl i vores angivne priser.
Endvidere forbeholder vi os ret til at ændre i priserne uden forudgående
samtykke. Der tages forbehold for udsolgte varer.


Betaling
".$company_name." modtager betaling med VISA-Dankort, VISA, VISA Electron,


Mastercard.
Betalingen vil først blive trukket på din konto, når varen afsendes.
Alle beløb er i kr.. Danske kroner og incl. moms.
Der tages forbehold for prisfejl og udsolgte/udgåede varer.


Reklamationsret 
Der gives 2 års reklamationsret i henhold til købeloven.

Vores reklamationsret gælder for fejl i materiale og/eller fabrikation.
Du kan få varen repareret, ombyttet, pengene retur eller afslag i prisen,
afhængig af den konkrete situation.
Reklamationen gælder ikke fejl eller skader begået ved forkert håndtering af
produktet/ydelsen.
Du skal reklamere i ”rimelig tid” efter du har opdaget manglen/fejlen.
Ved returnering, reklamationer og benyttelse af fortrydelseretten sendes til :
".$company_name."
".$address."
".$zipcode." ".$city."


Refusion 
Hvis der er tale om refusion, bedes du medsende bankoplysninger i form af
regnr og kontonr, så det aftalte beløb kan overføres. Disse oplysninger kan
uden risiko oplyses pr. mail eller anden elektronisk form, da det ikke er
følsomme oplysninger og kun vil blive anvendt til vores opfyldelse af
refusionen.


Fortrydelsesret 
Der gives 14 dages fuld returret på varer købt i vores webshop.

Inden 14 dage regnet fra modtagelsestidspunktet skal du give meddelelse
om, at du ønsker at fortryde dit køb.
Meddelelsen skal gives pr. mail på ".$email["email"]." I meddelelsen skal du
gøre os tydeligt opmærksom på, at du ønsker at udnytte din fortrydelsesret.

Du kan ikke fortryde ved blot at nægte modtagelse af varen, uden samtidig
at give tydelig meddelelse herom.


Varer undtaget fortrydelsesretten
Følgende varetyper indgår ikke i fortrydelsesretten: 
- Varer som er fremstillet efter forbrugerens specifikationer eller har
fået et tydeligt personligt præg.


- Varer, der grundet sin art bliver uløseligt blandet sammen med
andre ved levering.
- Udførte ikke-finansielle tjenesteydelser, hvis levering af tjeneste-
ydelsen er påbegyndt med forbrugerens forudgående udtrykkelige
samtykke og anderkendelse af, at fortrydelseretten ophører, når
tjenesteydelsen er fuldt udført.

Levering
Leveringstiden for hele Danmark er 1-3 hverdage på lagervarer, fra du har afgivet din bestilling, til du modtager den med posten. I tilfældet af at en vare er udsolgt efter bestillingen er foretaget, vil vi kontakte dig herom og returnere beløbet til dig, hvis du har betalt online.

Returnering 
Du skal sende din ordre retur uden unødig forsinkelse og senest 14 dage
efter, at du har gjort brug af din fortrydelsesret. Du skal afholde de direkte
udgifter i forbindelse med returnering. Ved returnereing er du ansvarlig for, at
varen er pakket ordentligt ind.
Du skal vedlægge en kopi af ordrebekræftelsen. Ekspeditionen går hurtigere
hvis du ligeledes udfylder og vedlægger vores Fortrydelsesformular.

Du bærer risikoen for varen fra tidpunket for varens levering og til vi har
modtaget den retur.
Vi modtager ikke pakker sendt pr. efterkrav.


Varens stand ved returnering
Du hæfter kun for eventuel forringelse af varens værdi, som skyldes anden
håndtering, end hvad der er nødvendigt for at fastslå varens art, egenskaber
og den måde, hvorpå den fungerer. Du kan med andre ord prøve varen, som
hvis du prøvede den i en fysisk butik.
Hvis varen er prøvet udover, det ovenfor beskrevet, betragtes den som
brugt. Hvilket betyder, at du ved fortrydelse af købet kun får en del eller intet
af købsbeløbet retur, afhængig af varens handelsmæssige værdi på
modtagelsestidspunktet - af returneringen.
For at modtage hele købsbeløbet retur må du altså afprøve varen uden
egentlig at tage den i brug.


Tilbagebetaling 
Fortryder du dit køb, får du naturligvis det beløb du har indbetalt til os retur.
I tilfælde af en værdiforringelse, som du hæfter for, fratrækkes denne købs-
beløbet.
Ved anvendelse af fortrydelsesretten, refunderes alle betalinger modtaget
fra dig, herunder leveringsomkostninger (undtaget ekstra omkostninger som
følge af dit valg af en anden leveringsform end den billigste form for standard
levering, som vi tilbyder), uden unødig forsinkelse og senest 14 dage fra den
dato, hvor vi har modtaget meddelelse om din beslutning om at gøre brug af
fortrydelsesretten.
Tilbagebetaling gennemføres med samme betalingsmiddel, som du
benyttede ved den oprindelige transaktion, med mindre du udtrykkeligt har
indvilget i noget andet.
Vi kan tilbageholde beløbsrefunderingen, indtil vi har modtaget varen retur,
med mindre du inden da har fremlagt dokumentation for at have returneret
den.
";

?>