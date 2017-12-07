<?php

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $menuitem = MenuItem::create([
            'provider_id'   => 4, //panera
            'item_name'     => 'Greek Yogurt with apple',
            'description'     => 'Greek Yogurt, Granola, and Mixed Berry Parfait with apple',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 4,
            'item_name'     => 'Half Salad w/ Chicken, apple',
            'description'     => 'Half Seasonal Greens Salad with Chicken and apple',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 4,
            'item_name'     => 'Half Salad w/ Chicken, roll',
            'description'     => 'Half Seasonal Greens Salad with Chicken and roll',
            'price'         => 500,
        ]);
//(1, 4, 'Half Greek Salad with roll',
//(2, 4, 'Half Caesar Salad with roll',
//(3, 4, 'Half Seasonal Greens with roll',
//(5, 4, 'Half Southwest Caesar Salad with roll',
//(6, 4, 'Turkey and Cheese Sandwich with apple',
//(7, 4, 'Ham and Swiss Sandwich with apple',
//(8, 4, 'PB and J Sandwich with apple',
//(9, 4, 'Macaroni and Cheese with roll',
//(55, 4, 'Chicken Noodle Soup with roll',
//(56, 4, 'Broccoli Cheddar Soup with roll',
//(57, 4, 'Creamy Tomato Soup with roll',
//(182, 4, 'Bistro French Onion Soup with roll',
//(184, 4, 'Baked Potato Soup with roll',
//(236, 4, 'Macaroni and Cheese with apple',
//(237, 4, 'Baked Potato Soup with apple',
//(238, 4, 'Bistro French Onion Soup with apple',
//(239, 4, 'Broccoli Cheddar Soup with apple',
//(240, 4, 'Chicken Noodle Soup with apple',
//(241, 4, 'Creamy Tomato Soup with apple',
//(242, 4, 'Half Caesar Salad with apple',
//(243, 4, 'Half Greek Salad with apple',
//(244, 4, 'Half Seasonal Greens with apple',
//(245, 4, 'Half Southwest Caesar Salad with apple',
//(246, 4, 'Extra Chips', 50,
//(247, 4, 'Add Roll', 50,
//(248, 4, 'Add Apple', 50,
//(316, 4, 'Greek Yogurt, Granola, and Mixed Berry Parfait with apple',
//(317, 4, 'Cup of Fruit', 300,
//(318, 4, 'Superfruit Smoothie with Greek Yogurt',
//(319, 4, 'Mango Smoothie with Greek yogurt',
//(320, 4, 'Green Passion Smoothie',
//(321, 4, 'Peach and Blueberry Smoothie with Almond Milk',
//(322, 4, 'Strawberry Smoothie with Greek Yogurt',
//(323, 4, 'Grilled Cheese Sandwich and apple',
//(324, 4, 'Half Seasonal Greens Salad with Chicken and apple', 650,
//(325, 4, 'Half Caesar Salad with Chicken and apple', 650,
//(326, 4, 'Half Greek Salad with Chicken with roll', 650,
//(327, 4, 'Half Caesar Salad with chicken and roll', 650,
//(328, 4, 'Half Seasonal Greens Salad with Chicken and roll', 650,
//(350, 4, 'Half Greek Salad with Chicken with apple', 650,

        $menuitem = MenuItem::create([
            'provider_id'   => 5, //chick
            'item_name'     => 'Grilled Chicken Sub, Fruit Cup',
            'description'     => 'Chilled Grilled Chicken Sub Sandwich with Fruit Cup',
            'price'         => 550,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 5,
            'item_name'     => 'Grilled Chicken Sub, Chips',
            'description'     => 'Chilled Grilled Chicken Sub Sandwich with Chips',
            'price'         => 500,
        ]);
//(19, 5, 'Chicken Salad Cup, fruit cup', 550, 0,
//(20, 5, 'Chicken Sandwich, fruit cup', 550,
//(21, 5, 'Chicken Sandwich, chips',
//(22, 5, '4 Chicken Nuggets, fruit cup', 350,
//(23, 5, '4 Chicken Nuggets, chips', 300,
//(24, 5, '6 Chicken Nuggets, fruit cup', 450,
//(25, 5, '6 Chicken Nuggets, chips', 400,
//(26, 5, '8 Chicken Nuggets, fruit cup', 550,
//(27, 5, '8 Chicken Nuggets with chips',
//(64, 5, 'Grilled Chicken Cool Wrap with Chips',
//(65, 5, 'Chilled Grilled Chicken Sub Sandwich with Chips',
//(66, 5, 'Veggie Wrap with Chips',
//(167, 5, 'Small Super Food Salad with Chips', 400,
//(168, 5, 'Large Superfood Salad with Chips',
//(169, 5, 'Small Super Food Salad with Fruit Cup', 450,
//(171, 5, 'Large Superfood Salad with Fruit Cup', 550,
//(172, 5, 'Chilled Grilled Chicken Sub Sandwich with Fruit Cup', 550,
//(173, 5, 'Grilled Chicken Cool Wrap with Fruit Cup', 550,
//(174, 5, 'Veggie Wrap with Fruit Cup', 550,
//(175, 5, 'Greek Yogurt Parfait with Chips',
//(176, 5, 'Greek Yogurt with Fruit Cup',
//(177, 5, 'Side Salad with Chips', 400,
//(178, 5, 'Side Salad with Fruit Cup', 450,
//(179, 5, 'Extra Four Piece Nugget', 200,
//(180, 5, 'Extra Chicken Sandwich', 400,
//(330, 5, 'Small Side of Baked Beans with chips', 350,
//(331, 5, 'Large Side of Baked Beans with chips',
//(332, 5, 'Small Mac and Cheese with Chips', 300,
//(333, 5, 'Medium Mac and Cheese with chips', 350,
//(334, 5, 'Large Mac and Cheese with chips',
//(335, 5, 'Spicy Chicken Sandwich and fruit cup', 550,
//(336, 5, 'Spicy Chicken Sandwich and chips',
//(337, 5, 'Large Mac and Cheese with Fruit Cup', 550,
//(338, 5, 'Large side of Baked Beans with Fruit Cup', 550,
//(339, 5, 'Medium Mac and Cheese with Fruit Cup', 400,
//(340, 5, 'Small Mac and Cheese with Fruit Cup', 350,
//(341, 5, 'Small Side of Baked Beans with Fruit Cup', 400,
//(249, 5, 'Extra Chips', 50,
//(256, 5, 'Add Fruit Cup', 300,

        $menuitem = MenuItem::create([
            'provider_id'   => 6, //elmers
            'item_name'     => 'Cheeseburger w/ chips',
            'description'     => 'Cheeseburger (lettuce and tomato on the side) with chips, cheese, and salsa',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 6,
            'item_name'     => 'Hamburger w/ chips',
            'description'     => 'Hamburger (lettuce and tomato on the side) with chips, cheese, and salsa',
            'price'         => 500,
        ]);
//(30, 6, 'Chicken Mini Burro with Chips, Cheese, and Salsa',
//(31, 6, 'Machaca Mini Burro with Chips, Cheese, and Salsa',
//(32, 6, 'Bean and Cheese Burrito with Chips, Cheese, and Salsa',
//(33, 6, 'Three Chicken Taquitos with Chips, Cheese, and Salsa',
//(34, 6, 'Three Beef Taquitos with Chips, Cheese, and Salsa',
//(35, 6, 'Two Beef Fried Tacos with Chips, Cheese, and Salsa',
//(36, 6, 'Two Chicken Fried Tacos with Chips, Cheese, and Salsa',
//(37, 6, 'Cheese Quesadilla with Chips, Cheese, and Salsa',
//(70, 6, '2 Cheese Enchiladas with Chips, Cheese, and Salsa',
//(71, 6, 'Mini Beef Chimis with Chips, Cheese, and Salsa',
//(72, 6, 'Mini Chicken Chimis with Chips, Cheese, and Salsa',
//(73, 6, 'Mini Red Chili Burro with Chips, Cheese, and Salsa',
//(74, 6, 'Garden Salad with Chips, Cheese, and Salsa',
//(250, 6, 'Extra Chips', 50,
//(311, 6, 'Hamburger (lettuce and tomato on the side) with chips, cheese, and salsa',
//(312, 6, 'Cheeseburger (lettuce and tomato on the side) with chips, cheese, and salsa',
//(313, 6, 'Corndog with chips, cheese, and salsa',
//(314, 6, 'Side of Rice and side of Beans with chips, cheese, and salsa',
//(315, 6, 'Side of Guacamole with chips, cheese, and salsa',

        $menuitem = MenuItem::create([
            'provider_id'   => 7, //floridinos
            'item_name'     => 'Side Salad',
            'description'     => 'Side Salad with Ranch or Italian dressing (Italian is gluten free)',
            'price'         => 300,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 7,
            'item_name'     => 'Pepperoni Pizza Muffins (4)',
            'description'     => 'Pepperoni Pizza Muffins (4) with marinara dipping sauce',
            'price'         => 500,
        ]);
//(46, 7, 'Cheese Pizza Muffins (4) with ranch dipping sauce',
//(47, 7, 'Pepperoni Pizza Muffins (4) with marinara dipping sauce',
//(80, 7, 'Gluten Free Pasta with Marinara',
//(81, 7, 'Ziti with Alfredo Sauce and Roll',
//(82, 7, 'Side Salad with Ranch or Italian dressing (Italian is gluten free)', 300,
//(188, 7, 'Ziti with Marinara and roll',
//(189, 7, 'Ziti with butter and roll',
//(215, 7, 'Cheese Pizza Muffins (4) with marinara dipping sauce',
//(216, 7, 'Pepperoni Pizza Muffins (4) with ranch dipping sauce',
//(252, 7, 'Extra Chips', 50,

        $menuitem = MenuItem::create([
            'provider_id'   => 8, //firehouse
            'item_name'     => 'Grilled Cheese, Turkey, Broccoli Cheese Soup',
            'description'     => 'Grilled Cheese with Turkey and Tomato and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Grilled Cheese, Turkey, Chicken Soup',
            'description'     => 'Half Grilled Cheese with Turkey and Tomato and Chicken Noodle Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup1',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup2',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup3',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup4',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup5',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup6',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup7',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup8',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup9',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup10',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup11',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup12',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup13',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup14',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup15',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup16',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup17',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup18',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup19',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup20',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup21',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup22',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup23',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup24',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup25',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup26',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup27',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup28',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup29',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup30',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup31',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup32',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup33',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup34',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup35',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup36',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup37',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup38',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 8,
            'item_name'     => 'Side Salad, Broccoli Soup39',
            'description'     => 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
            'price'         => 500,
        ]);
        //(149, 33, 'Cold Ham and Provolone Sub',
//(150, 33, 'Cold Turkey and Provolone Sub',
//(151, 33, 'Hot Meatball Sub',
//(152, 33, 'Hot Grilled Cheese',
//(160, 33, 'Beef Chili with Saltines',
//(185, 33, 'Side Salad with Ranch', 400,
//(186, 33, 'Cold Ham and Provolone Sub (Meat and Cheese Only)',
//(187, 33, 'Cold Turkey and Provolone Sub (Meat and Cheese Only)',
//(251, 33, 'Extra Chips', 50,
//(258, 33, 'Chicken Noodle Soup',
//(261, 33, 'Half Turkey Sub and Chicken Noodle Soup',
//(262, 33, 'Half Ham Sub and Chicken Noodle Soup',
//(263, 33, 'Half Plain Turkey Sub and Chicken Noodle Soup',
//(264, 33, 'Half Plain Ham Sub and Chicken Noodle Soup',
//(265, 33, 'Half Grilled Cheese and Chicken Noodle Soup',
//(285, 33, 'Side Salad with Ranch and Broccoli Cheese Soup',
//(286, 33, 'Side Salad with Ranch and Chicken Noodle Soup',
//(287, 33, 'Half Grilled Cheese and Broccoli Cheese Soup',
//(288, 33, 'Half Plain Ham Sub and Broccoli Cheese Soup',
//(289, 33, 'Half Ham Sub with Broccoli Cheese Soup',
//(290, 33, 'Half Plain Turkey Sub and Broccoli Cheese Soup',
//(291, 33, 'Half Turkey Sub with Broccoli Cheese Soup',
//(292, 33, 'Broccoli Cheese Soup',
//(295, 33, 'Grilled Cheese with Ham and Tomato',
//(296, 33, 'Grilled Cheese with Ham',
//(298, 33, 'Grilled Cheese with Turkey',
//(299, 33, 'Half Grilled Cheese with Ham and Tomato and Chicken Noodle Soup',
//(301, 33, 'Half Grilled Cheese with Ham and Tomato and Broccoli Cheese Soup',
//(302, 33, 'Half Grilled Cheese with Ham and Chicken Noodle Soup',
//(304, 33, 'Half Grilled Cheese with Ham and Broccoli Cheese Soup',
//(305, 33, 'Half Grilled Cheese with Turkey and Tomato and Chicken Noodle Soup',
//(307, 33, 'Half Grilled Cheese with Turkey and Tomato and Broccoli Cheese Soup',
//(308, 33, 'Half Grilled Cheese with Turkey and Chicken Noodle Soup',
//(310, 33, 'Half Grilled Cheese with Turkey and Broccoli Cheese Soup',
//(294, 33, 'Pepperoni and Meatball Sub',
//(342, 33, 'Italian Sub with lettuce and tomato',
//(343, 33, 'Italian Sub (Meat and Cheese only)',
//(344, 33, 'Side Salad with Italian Dressing and Broccoli Cheese Soup',
//(345, 33, 'Side Salad with Italian Dressing', 400,
//(346, 33, 'Side Salad with Honey Mustard Dressing', 400,
//(347, 33, 'Side Salad with Italian Dressing and Chicken Noodle Soup',
//(348, 33, 'Side Salad with Honey Mustard Dressing and Broccoli Cheese Soup',
//(349, 33, 'Side Salad with Honey Mustard Dressing and Chicken Noodle Soup',

        $menuitem = MenuItem::create([
            'provider_id'   => 9,
            'item_name'     => 'Sweet & Sour Chicken, white rice',
            'description'     => 'Gluten Free Sweet and Sour Chicken with white rice',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 9,
            'item_name'     => 'Sweet & Sour Chicken, brown rice',
            'description'     => 'Gluten Free Sweet and Sour Chicken with brown rice',
            'price'         => 500,
        ]);
//(181, 34, 'Gluten Free Chopped Chicken Salad',
//(200, 34, 'Honey Seared Chicken with white rice',
//(201, 34, 'Honey Seared Chicken with brown rice',
//(202, 34, 'Honey Seared Chicken with noodles',
//(204, 34, 'Teryaki Chicken with white rice',
//(205, 34, 'Teryaki Chicken with brown rice',
//(206, 34, 'Teryaki Chicken with noodles',
//(208, 34, 'Sweet and Sour Chicken with white rice',
//(209, 34, 'Sweet and Sour Chicken with brown rice',
//(210, 34, 'Sweet and Sour Chicken with noodles',
//(212, 34, 'Gluten Free Sweet and Sour Chicken with white rice',
//(214, 34, 'Gluten Free Sweet and Sour Chicken with brown rice',
//(255, 34, 'Extra Chips', 50,
//(329, 34, 'Half Order of Lettuce Wraps',


        $menuitem = MenuItem::create([
            'provider_id'   => 10,
            'item_name'     => 'Chicken Salad on Croissant',
            'description'     => 'Chicken Salad with Almonds and Pineapple on All Butter Croissant',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 10,
            'item_name'     => 'Chicken Salad on Wheat Wrap',
            'description'     => 'Chicken Salad with Almonds and Pineapple on Organic Wheat Wrap',
            'price'         => 500,
        ]);
//(165, 35, 'Nitrate Free Ham on All Butter Croissant',
//(190, 35, 'Roasted Turkey Breast on All Butter Croissant',
//(191, 35, 'Chicken Salad with Almonds and Pineapple on All Butter Croissant',
//(192, 35, 'Vegetarian:  Spinach Veggie Wrap',
//(193, 35, 'Chicken Salad with Almonds and Pineapple on Organic Wheat Wrap',
//(194, 35, 'Chicken Salad with Almonds and Pineapple on Telera Bun',
//(195, 35, 'Nitrate Free Ham on Organic Wheat Wrap',
//(196, 35, 'Nitrate Free Ham on Telera Bun',
//(197, 35, 'Roasted Turkey Breast on Organic Wheat Wrap',
//(198, 35, 'Roasted Turkey Breast on Telera Bun',
//(253, 35, 'Extra Chips', 50,

        $menuitem = MenuItem::create([
            'provider_id'   => 11,
            'item_name'     => '(2) Turkey Slider Sandwiches',
            'description'     => 'Slider Sandwiches (2) with Turkey and Cheese with lettuce and tomato',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 11,
            'item_name'     => '(2) Plain Pepperoni Sliders',
            'description'     => 'Slider Sandwiches (2) with Pepperoni and Cheese- Plain',
            'price'         => 500,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 11,
            'item_name'     => '(2) Lettuce, Tomato, Pepperoni Sliders',
            'description'     => 'Slider Sandwiches (2) with Pepperoni and Cheese- lettuce and tomato',
            'price'         => 500,
        ]);
//(217, 36, 'Slider Sandwiches (2) with Ham and Cheese- Plain',
//(218, 36, 'Slider Sandwiches (2) with Turkey and Cheese- Plain',
//(219, 36, 'Slider Sandwiches (2) with Turkey and Cheese with lettuce and tomato',
//(220, 36, 'Slider Sandwiches (2) with Pepperoni and Cheese- Plain',
//(221, 36, 'Slider Sandwiches (2) with Pepperoni and Cheese- lettuce and tomato',
//(222, 36, 'Sub with Ham and Cheese- Plain',
//(223, 36, 'Sub with Ham and Cheese- lettuce and tomato',
//(224, 36, 'Sub with Turkey and Cheese- Plain',
//(225, 36, 'Sub with Turkey and Cheese- lettuce and tomato',
//(226, 36, 'Sub with Pepperoni and Cheese- Plain',
//(227, 36, 'Sub with Pepperoni and Cheese- lettuce and tomato',
//(228, 36, 'Small Gluten Free Chicken Salad',
//(229, 36, 'Small Oriental Chicken Salad',
//(230, 36, 'Small Cobb Salad',
//(231, 36, 'Cup of Carrot and Celery Sticks in Ranch', 350,
//(232, 36, 'Fruit Cup', 350,
//(233, 36, 'Yogurt Parfait', 350,
//(166, 36, 'Slider Sandwiches (2) with Ham and Cheese, lettuce and tomato',
//(254, 36, 'Extra Chips', 50,

        $menuitem = MenuItem::create([
            'provider_id'   => 12,
            'item_name'     => 'Baja Chicken Burrito, Chips',
            'description'     => 'Baja Chicken Burrito with Chips and Salsa',
            'price'         => 600,
        ]);
        $menuitem = MenuItem::create([
            'provider_id'   => 12,
            'item_name'     => 'Chipotle Salad, Chicken',
            'description'     => 'Chipotle Ranch Salad with Grilled Chicken',
            'price'         => 600,
        ]);
//(270, 37, 'Chicken Tacos (2) with chips',
//(271, 37, 'Kids Cheese Quesadilla', 350,
//(272, 37, 'Steak Tacos (2) with Chips', 500,
//(273, 37, 'Kids Quesadilla with Chicken', 400,
//(274, 37, 'Kids Bean and Cheese Burrito', 350,
//(275, 37, 'Chicken Taco (1)', 350,
//(276, 37, 'Steak Taco (1)', 350,
//(277, 37, 'Baja Chicken Burrito with Chips and Salsa', 600,
//(278, 37, 'Chipotle Ranch Salad with Grilled Chicken', 600,
//(279, 37, 'Chipotle Ranch Salad with Black Beans', 600,
//(280, 37, 'California Bowl with Grilled Chicken', 600,
//(281, 37, 'California Bowl with Grilled Veggies', 550,
//(282, 37, 'Side of Chips and Salsa', 150,
//(283, 37, 'Kids Churro', 150,
//(293, 37, 'Extra Chips', 50,
    }
}
