<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('name', 'admin'))->first();

        $items = [
            // Wapens
            ['name' => 'Schaduwvloekzwaard',    'description' => 'Een kling gesmeed in het schaduwrijk, hij fluistert van donkere energie en doorsnijdt magische barrières.',    'type' => 'weapon',      'rarity' => 'legendary', 'strength' => 95, 'speed' => 70, 'durability' => 80, 'magic_property' => 'Schaduwstoot: deelt extra leegteschade bij kritieke treffers',                  'required_level' => 40],
            ['name' => 'IJzeren Langschwaard',   'description' => 'Een stevig en betrouwbaar zwaard gedragen door vele avonturiers door het rijk.',                               'type' => 'weapon',      'rarity' => 'common',    'strength' => 30, 'speed' => 40, 'durability' => 60, 'magic_property' => null,                                                                            'required_level' => 1],
            ['name' => 'Vorstbeet Dolk',         'description' => 'Een kort mes betoverd met ijsmagie. Vertraagt vijanden bij een treffer.',                                      'type' => 'weapon',      'rarity' => 'rare',      'strength' => 55, 'speed' => 85, 'durability' => 45, 'magic_property' => 'Vorstvertraging: vermindert vijandelijke snelheid met 25% gedurende 3 seconden',   'required_level' => 20],
            ['name' => 'Stormroeper Staf',       'description' => 'Kanaalt bliksem door zijn oud hout. Geeft meesterschap over stormspreuken.',                                    'type' => 'weapon',      'rarity' => 'epic',      'strength' => 78, 'speed' => 35, 'durability' => 50, 'magic_property' => 'Kettingbliksem: kans om schade te laten overspringen naar nabijgelegen vijanden',  'required_level' => 30],
            ['name' => 'Jager Kortboog',         'description' => 'Een lichtgewicht boog gemaakt van elvenpijnhout, geliefd bij verkenners en schutters.',                         'type' => 'weapon',      'rarity' => 'uncommon',  'strength' => 45, 'speed' => 75, 'durability' => 55, 'magic_property' => 'Arendsoog: +10% nauwkeurigheid op lange afstand',                               'required_level' => 10],
            ['name' => 'Verroeste Bijl',         'description' => 'Een versleten bijl gevonden in de kerker. Niet mooi, maar slaat nog hard.',                                     'type' => 'weapon',      'rarity' => 'common',    'strength' => 25, 'speed' => 30, 'durability' => 35, 'magic_property' => null,                                                                            'required_level' => 1],

            // Uitrusting
            ['name' => 'Drakenscubbenpantser',   'description' => 'Gesmeed van de schubben van een oude draak. Bijna ondoordringbaar, zwak glanzend van drakenenergie.',           'type' => 'armor',   'rarity' => 'legendary', 'strength' => 20, 'speed' => 25, 'durability' => 98, 'magic_property' => 'Drakenschild: 15% kans om magische schade terug te kaatsen naar de aanvaller',  'required_level' => 45],
            ['name' => 'Maliënkolder',           'description' => 'Aaneengesloten ijzeren ringen bieden solide bescherming zonder te veel mobiliteit op te offeren.',              'type' => 'armor',   'rarity' => 'common',    'strength' => 10, 'speed' => 50, 'durability' => 65, 'magic_property' => null,                                                                            'required_level' => 1],
            ['name' => 'Maanweefsel Gewaad',     'description' => 'Geweven van maanlichtvezels, dit gewaad verbetert magische vaardigheden en glinstert in het donker.',           'type' => 'armor',   'rarity' => 'epic',      'strength' =>  5, 'speed' => 70, 'durability' => 40, 'magic_property' => 'Maanbarrière: absorbeert tot 200 magische schade per gevecht',                    'required_level' => 28],
            ['name' => 'Woud Leer Wapenrusting', 'description' => 'Lichtgewicht wapenrusting gemaakt van gelooide woudhuiden, populair bij schurken en schutters.',                 'type' => 'armor',   'rarity' => 'uncommon',  'strength' => 15, 'speed' => 65, 'durability' => 55, 'magic_property' => 'Camouflage: verkleint het detectiebereik van vijanden met 20%',                  'required_level' => 8],
            ['name' => 'Stalen Borstplaat',      'description' => 'Standaard uitrusting voor bewakers en soldaten door het hele koninkrijk.',                                       'type' => 'armor',   'rarity' => 'common',    'strength' => 12, 'speed' => 40, 'durability' => 70, 'magic_property' => null,                                                                            'required_level' => 5],
            ['name' => 'Afgronds Mantel',        'description' => 'Een mantel doordrenkt met leegte-energie, waardoor de drager gedeeltelijk onzichtbaar wordt in zwak licht.',  'type' => 'armor',   'rarity' => 'rare',      'strength' =>  8, 'speed' => 80, 'durability' => 45, 'magic_property' => 'Fasesprong: 10% kans om fysieke aanvallen volledig te ontwijken',              'required_level' => 18],

            // Accessoires
            ['name' => 'Amulet van het Eeuwige Leven', 'description' => 'Een gouden amulet gloeiend van helende magie. Regenereert langzaam de gezondheid van de drager.',     'type' => 'accessory', 'rarity' => 'legendary', 'strength' =>  0, 'speed' =>  0, 'durability' => 90,  'magic_property' => 'Regeneratie: herstelt 5 LP per seconde passief',                              'required_level' => 35],
            ['name' => 'Ring van Behendigheid',       'description' => 'Een betoverde ring die de drager zo licht als een veertje laat voelen.',                               'type' => 'accessory', 'rarity' => 'rare',      'strength' =>  5, 'speed' => 90, 'durability' => 70,  'magic_property' => 'Haast: bewegingssnelheid verhoogd met 30%',                                   'required_level' => 15],
            ['name' => 'Geluksmunt',                  'description' => 'Een oude munt glad gesleten. Zou geluk brengen aan degenen die hem bij zich dragen.',                  'type' => 'accessory', 'rarity' => 'common',    'strength' =>  0, 'speed' =>  0, 'durability' => 100, 'magic_property' => 'Fortuin: +2% kans op zeldzame voorwerpdrops',                                'required_level' => 1],
            ['name' => 'Arcaan Focuskristal',         'description' => 'Versterkt magische spreuken als het vastgehouden wordt. Een must voor elke serieuze tovenaar.',       'type' => 'accessory', 'rarity' => 'epic',      'strength' =>  0, 'speed' =>  0, 'durability' => 60,  'magic_property' => 'Spreukversterkig: alle spreuken doen 25% extra schade',                        'required_level' => 25],
            ['name' => 'IJzeren Polsbeschermers',     'description' => 'Eenvoudige ijzeren polsbeschermers die een extra klap geven bij nabijgevechtsslagen.',               'type' => 'accessory', 'rarity' => 'common',    'strength' => 20, 'speed' =>  0, 'durability' => 65,  'magic_property' => null,                                                                            'required_level' => 1],
            ['name' => 'Laarzen van de Wind',         'description' => 'Oude laarzen die fluisteren van windgeesten. Verdubbelt bijna de bewegingssnelheid voor korte uitbarstingen.', 'type' => 'accessory', 'rarity' => 'epic', 'strength' => 0, 'speed' => 95, 'durability' => 55, 'magic_property' => 'Windrush: sprintsnelheid verdubbeld gedurende 5 seconden, 30s cooldown',      'required_level' => 22],

            // Verbruiksartikelen
            ['name' => 'Grote Genezingsdrank', 'description' => 'Een levendig rode drank die een aanzienlijk deel van de gezondheid herstelt bij consumptie.',              'type' => 'consumable', 'rarity' => 'uncommon', 'strength' =>   0, 'speed' => 0, 'durability' => 100, 'magic_property' => 'Onmiddellijk Herstel: herstelt 150 LP direct',                                'required_level' => 1],
            ['name' => 'Elixer van Kracht',    'description' => 'Verdubbelt tijdelijk de kracht van de drinker voor 5 minuten. Smaakt verdacht naar verbrande karamel.',    'type' => 'consumable', 'rarity' => 'rare',     'strength' => 100, 'speed' => 0, 'durability' => 100, 'magic_property' => 'Krachtstoot: kracht verdubbeld gedurende 5 minuten, eenmalig gebruik',          'required_level' => 10],
        ];

        foreach ($items as $data) {
            Item::create(array_merge($data, ['created_by' => $admin?->id]));
        }
    }
}
