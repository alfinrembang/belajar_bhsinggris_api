<?php

namespace Database\Seeders;

use App\Models\WerewolfSoal;
use Illuminate\Database\Seeder;

class WerewolfGameSeeder extends Seeder
{
    public function run(): void
    {
        $json = <<<'JSON'
[
    {
        "theme": "Food & Sweet",
        "domba_word": "BANANA",
        "domba_meaning": "Pisang",
        "domba_clues": [
            "It is a sweet fruit that grows in clusters.",
            "It has a yellow peel that you take off before eating.",
            "Monkeys really love to eat this.",
            "It is rich in potassium and great for energy."
        ],
        "serigala_word": "CHOCOLATE",
        "serigala_meaning": "Coklat",
        "serigala_clues": [
            "It is sweet and loved by many kids and adults.",
            "It is often brown and melts in your mouth.",
            "People eat it as a delicious dessert or snack.",
            "It is commonly used as a topping or flavor."
        ],
        "english_difference": "Banana is a yellow tropical fruit, while Chocolate is a sweet food made from roasted cacao beans."
    },
    {
        "theme": "Animals",
        "domba_word": "CAT",
        "domba_meaning": "Kucing",
        "domba_clues": [
            "It is a small furry pet that lives with humans.",
            "It makes a gentle purring sound when happy.",
            "It loves catching mice and playing with yarn.",
            "It has whiskers and soft paws."
        ],
        "serigala_word": "TIGER",
        "serigala_meaning": "Harimau",
        "serigala_clues": [
            "It is a powerful animal with sharp claws and teeth.",
            "It belongs to the feline family and loves meat.",
            "It has beautiful orange fur with dark stripes.",
            "It hunts prey quietly in the wilderness."
        ],
        "english_difference": "Cat is a small domesticated pet, while Tiger is the largest wild cat species with stripes."
    },
    {
        "theme": "Musical Instruments",
        "domba_word": "GUITAR",
        "domba_meaning": "Gitar",
        "domba_clues": [
            "It usually has six strings and a wooden body.",
            "You play it by strumming or plucking with fingers.",
            "Very popular in pop, acoustic, and rock music bands.",
            "It has frets along its long neck."
        ],
        "serigala_word": "VIOLIN",
        "serigala_meaning": "Biola",
        "serigala_clues": [
            "It has four strings and produces a high pitch.",
            "You often use a horsehair bow to produce sound.",
            "Very famous in classical orchestra concerts.",
            "You rest it between your shoulder and chin to play."
        ],
        "english_difference": "Guitar has six strings and is strummed, while Violin has four strings and is played using a bow."
    },
    {
        "theme": "Beverages",
        "domba_word": "COFFEE",
        "domba_meaning": "Kopi",
        "domba_clues": [
            "It is a dark brew made from roasted beans.",
            "Many adults drink it in the morning to stay awake.",
            "It has caffeine and a strong aromatic smell.",
            "People often add milk, sugar, or creamer to it."
        ],
        "serigala_word": "TEA",
        "serigala_meaning": "Teh",
        "serigala_clues": [
            "It is made by soaking dried leaves in hot water.",
            "Very popular in Asian and British culture in the afternoon.",
            "It can be green, black, or herbal with a soothing taste.",
            "People drink it hot or iced with lemon."
        ],
        "english_difference": "Coffee is brewed from ground coffee beans, while Tea is brewed by steeping Camellia sinensis leaves."
    },
    {
        "theme": "Transportation",
        "domba_word": "AIRPLANE",
        "domba_meaning": "Pesawat Terbang",
        "domba_clues": [
            "It has fixed wings and travels between long-distance cities.",
            "It takes off and lands on a long airport runway.",
            "It carries hundreds of passengers high in the sky.",
            "It has jet engines under its wings."
        ],
        "serigala_word": "HELICOPTER",
        "serigala_meaning": "Helikopter",
        "serigala_clues": [
            "It can take off and land vertically without a runway.",
            "It has large rotating blades spinning on top of it.",
            "It can hover stationary in mid-air during rescue missions.",
            "Used often in police patrols and medical evacuations."
        ],
        "english_difference": "Airplane has fixed wings and needs a runway, while Helicopter uses spinning overhead rotors to hover and take off vertically."
    },
    {
        "theme": "Occupations",
        "domba_word": "DOCTOR",
        "domba_meaning": "Dokter",
        "domba_clues": [
            "A medical professional who diagnoses illnesses and writes prescriptions.",
            "Often wears a white coat and carries a stethoscope.",
            "Studies medicine for many years to perform surgery or treatments.",
            "Works in hospitals and clinics treating patients."
        ],
        "serigala_word": "NURSE",
        "serigala_meaning": "Perawat",
        "serigala_clues": [
            "Cares for hospital patients daily by taking vital signs.",
            "Assists physicians and administers prescribed medication.",
            "Wears medical scrubs and checks patient recovery.",
            "Known for kindness and empathy toward sick people."
        ],
        "english_difference": "Doctor diagnoses illnesses and prescribes medical treatment, while Nurse provides hands-on care and patient monitoring."
    },
    {
        "theme": "Astronomy",
        "domba_word": "SUN",
        "domba_meaning": "Matahari",
        "domba_clues": [
            "It is the gigantic star at the center of our solar system.",
            "It provides warmth and bright daylight to Earth.",
            "Plants need its light for photosynthesis.",
            "It rises in the east and sets in the west."
        ],
        "serigala_word": "MOON",
        "serigala_meaning": "Bulan",
        "serigala_clues": [
            "It is Earth natural rocky satellite shining in the night.",
            "It reflects sunlight and changes its visible phases.",
            "Astronauts landed on its dusty surface in 1969.",
            "It causes ocean tides with its gravitational pull."
        ],
        "english_difference": "Sun is a bright luminous star providing daylight, while Moon is a rocky satellite that orbits Earth and reflects sunlight."
    },
    {
        "theme": "Fruits",
        "domba_word": "APPLE",
        "domba_meaning": "Apel",
        "domba_clues": [
            "It is a round fruit with red or green smooth skin.",
            "It has a crunchy texture and white juicy flesh inside.",
            "Famous quote says eating one a day keeps the doctor away.",
            "Newton got inspired about gravity when it fell."
        ],
        "serigala_word": "ORANGE",
        "serigala_meaning": "Jeruk",
        "serigala_clues": [
            "It is a citrus fruit with a textured peel and segments.",
            "Rich in vitamin C with a sweet and sour citrus flavor.",
            "People frequently squeeze it to make fresh juice.",
            "Its name is also the name of a bright warm color."
        ],
        "english_difference": "Apple is a crunchy fruit with edible thin skin, while Orange is a citrus fruit with thick peel and segmented pulp."
    },
    {
        "theme": "Vehicles",
        "domba_word": "BICYCLE",
        "domba_meaning": "Sepeda",
        "domba_clues": [
            "Has two wheels and pedals that you push with your legs.",
            "Eco-friendly transport that does not use gasoline.",
            "Has handlebars, a bell, and a chain to drive the wheels.",
            "Great for exercise and leisurely riding in the park."
        ],
        "serigala_word": "MOTORCYCLE",
        "serigala_meaning": "Sepeda Motor",
        "serigala_clues": [
            "Has two wheels and an engine that runs on fuel.",
            "Much faster transport that requires wearing a helmet.",
            "Has an exhaust pipe and throttle on the handlebar.",
            "Commonly used for daily commuting on city roads."
        ],
        "english_difference": "Bicycle is human-powered by pedaling, while Motorcycle is powered by an internal combustion engine or battery."
    },
    {
        "theme": "Nature",
        "domba_word": "RIVER",
        "domba_meaning": "Sungai",
        "domba_clues": [
            "A natural flowing stream of fresh water.",
            "It flows across lands from mountains down to the sea.",
            "People can go rafting or fish along its muddy banks.",
            "Has riverbanks and a continuous flowing current."
        ],
        "serigala_word": "OCEAN",
        "serigala_meaning": "Samudra / Laut",
        "serigala_clues": [
            "A vast body of saltwater that covers most of planet Earth.",
            "Home to coral reefs, whales, sharks, and deep sea life.",
            "Has gigantic rolling waves and sandy beaches on its shores.",
            "Ships sail across its deep and mysterious expanse."
        ],
        "english_difference": "River is a freshwater body flowing along a channel, while Ocean is a massive saltwater body covering over 70% of Earth."
    },
    {
        "theme": "Sports",
        "domba_word": "BASKETBALL",
        "domba_meaning": "Bola Basket",
        "domba_clues": [
            "Played by dribbling an orange ball and shooting into an elevated hoop.",
            "Players bounce the ball on a wooden indoor court.",
            "Famous league is the NBA with 5 players per team.",
            "You score 2 or 3 points when the ball goes through the net."
        ],
        "serigala_word": "VOLLEYBALL",
        "serigala_meaning": "Bola Voli",
        "serigala_clues": [
            "Played over a tall net without letting the ball hit the floor.",
            "Players bump, set, and spike the ball with their hands.",
            "Often played on sand at the beach or on indoor gym courts.",
            "Each team has a maximum of three touches before passing over."
        ],
        "english_difference": "Basketball involves dribbling and shooting into an elevated hoop, while Volleyball involves hitting a ball over a high net without catching."
    },
    {
        "theme": "Education",
        "domba_word": "BOOK",
        "domba_meaning": "Buku",
        "domba_clues": [
            "Contains written pages bound together with a cover spine.",
            "You borrow it from libraries or read novels and textbooks.",
            "Can have chapters, an author, and hundreds of printed sheets.",
            "Students use it to study subjects in school."
        ],
        "serigala_word": "NEWSPAPER",
        "serigala_meaning": "Koran",
        "serigala_clues": [
            "Printed on large thin paper sheets published daily or weekly.",
            "Contains current news reports, articles, weather, and headlines.",
            "People read it in the morning alongside breakfast.",
            "Has big black bold headlines on the front page."
        ],
        "english_difference": "Book is bound pages of permanent literature or knowledge, while Newspaper is a daily/weekly periodical report on current events."
    },
    {
        "theme": "Furniture",
        "domba_word": "CHAIR",
        "domba_meaning": "Kursi",
        "domba_clues": [
            "A piece of furniture designed for one person to sit on.",
            "Usually has four legs and a firm backrest.",
            "Found at dining tables, study desks, and classrooms.",
            "Made of wood, plastic, or metal."
        ],
        "serigala_word": "SOFA",
        "serigala_meaning": "Sofa",
        "serigala_clues": [
            "A large upholstered couch that seats two or more people.",
            "Very comfortable with soft cushions and armrests.",
            "Located in the living room for relaxing and watching TV.",
            "People can lie down and take a nap on it."
        ],
        "english_difference": "Chair is a single-seat piece of furniture, while Sofa is a cushioned multi-person seating unit found in living rooms."
    },
    {
        "theme": "Clothing",
        "domba_word": "SHOES",
        "domba_meaning": "Sepatu",
        "domba_clues": [
            "Footwear that covers your entire foot and often ties with laces.",
            "You wear them with socks for school, sports, or formal events.",
            "Protects your feet completely from dirt, stones, and weather.",
            "Made of leather, canvas, or synthetic rubber."
        ],
        "serigala_word": "SANDALS",
        "serigala_meaning": "Sandal",
        "serigala_clues": [
            "Open footwear held onto the foot by straps or thongs.",
            "Leaves your toes and heels exposed to the cool breeze.",
            "Commonly worn at home, on the beach, or on casual sunny days.",
            "Quick and easy to slip on without tying shoelaces."
        ],
        "english_difference": "Shoes enclose the entire foot and are worn with socks, while Sandals are open-toed footwear secured by straps."
    },
    {
        "theme": "Weather",
        "domba_word": "RAIN",
        "domba_meaning": "Hujan",
        "domba_clues": [
            "Liquid water drops that fall from grey storm clouds.",
            "You need an umbrella or raincoat to stay dry outside.",
            "Makes the ground wet and creates puddles on the street.",
            "Accompanied by thunder, lightning, and cool breeze."
        ],
        "serigala_word": "SNOW",
        "serigala_meaning": "Salju",
        "serigala_clues": [
            "Frozen white ice crystals that gently float down in winter.",
            "Covers the land, roofs, and trees in a blanket of soft white.",
            "Children build snowmen and throw snowballs with it.",
            "It occurs only when temperatures drop below freezing point."
        ],
        "english_difference": "Rain is liquid water droplets falling from clouds, while Snow consists of frozen crystalline water droplets falling in winter."
    }
]
JSON;

        $pairs = json_decode($json, true);

        foreach ($pairs as $item) {
            WerewolfSoal::updateOrCreate(
                [
                    'domba_word' => strtoupper($item['domba_word']),
                    'serigala_word' => strtoupper($item['serigala_word']),
                ],
                [
                    'theme' => $item['theme'],
                    'domba_meaning' => $item['domba_meaning'],
                    'domba_clues' => $item['domba_clues'],
                    'serigala_meaning' => $item['serigala_meaning'],
                    'serigala_clues' => $item['serigala_clues'],
                    'english_difference' => $item['english_difference'],
                    'is_active' => true,
                ]
            );
        }
    }
}
