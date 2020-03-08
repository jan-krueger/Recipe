<template>
    <div>
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo">Recipes <3</a>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col offset-s1 s9">
                    <div class="input-field col s4">
                        <input id="search" type="text" v-model="filters.search_text">
                        <label for="search">Search</label>
                    </div>
                    <div class="input-field col s4">
                        <select class="icons" v-model.number="filters.dietary_preference">
                            <option value="-1">All</option>
                            <option value="0">Pescetarian</option>
                            <option value="1">Omnivore</option>
                            <option value="2">Vegetarian</option>
                            <option value="3">Vegan</option>
                        </select>
                        <label>Dietary Preference</label>
                    </div>
                    <div class="input-field col s4">
                        <select class="icons" v-model.number="filters.season">
                            <option value="-1" default>None</option>
                            <option value="0">Spring <i class="fa fa-seedling right right"></i></option>
                            <option value="1">Summer <i class="fa fa-umbrella-beach right"></i></option>
                            <option value="2">Autumn <i class="fa fa-cloud-showers-heavy right"></i></option>
                            <option value="3">Winter <i class="fa fa-snowman right"></i></option>
                        </select>
                        <label>Seasonality</label>
                    </div>
                </div>
                <div class="col offset-s1 s9">
                    <ul class="collapsible">
                        <li>
                            <div class="collapsible-header">Nutrition Filters</div>
                            <div class="collapsible-body">
                                <div class="row">
                                    <div class="col s3 nutrition-slider" v-for="nutrition in this.nutrition">
                                        <b>{{ nutrition.name }} [{{ nutrition.unit }}]</b>
                                        <div :id="'nutrition-slider-' + nutrition.id"></div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col s4" v-for="entry in _filtered_recipes">
                    <div class="card">
                        <div class="card-image">
                            <img :src="entry.cover" class="responsive-img filter-blur-2">
                            <span class="card-title ">{{ entry.recipe.name }}</span>
                            <a class="waves-effect waves-light btn-floating right-aligned halfway-fab" @click="_openModal(entry)">
                                <i class="fa fa-cookie"></i>
                            </a>
                        </div>

                        <!-- Modal Structure -->
                        <div :id="'modal' + entry.id" class="modal modal-fixed-footer">
                            <div class="modal-content" v-if="currentEntry !== null">
                                <div>
                                    <ul class="tabs center">
                                        <li class="tab col s3 white-text blue-grey"
                                            :class="{'darken-4': currentEntry.state.tab.index === 0}" @click="currentEntry.state.tab.index = 0">
                                            Information <i class="fa fa-info"></i></li>
                                        <li class="tab col s3 white-text blue-grey"
                                            :class="{'darken-4': currentEntry.state.tab.index === 1}" @click="currentEntry.state.tab.index = 1">
                                            Prepare <i class="fa fa-shopping-basket"></i> </li>
                                        <li class="tab col s3 white-text blue-grey"
                                            :class="{'darken-4': currentEntry.state.tab.index === 2}" @click="currentEntry.state.tab.index = 2">
                                            Cook</li>
                                        <li class="tab col s3 white-text blue-grey"
                                            :class="{'darken-4': currentEntry.state.tab.index === 3}" @click="currentEntry.state.tab.index = 3">
                                            Enjoy <i class="fa fa-glass-cheers"></i></li>
                                    </ul>
                                    <div class="progress red lighten-2" style="margin-top: 0; border-radius: 0;">
                                        <div class="determinate" :style="{width: _current_recipe_progress + '%'}"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col s12" v-if="currentEntry.state.tab.index === 0">
                                        <div class="row">
                                            <div class="col s6">
                                                <div class="collection">
                                                    <a v-for="row in entry._nutrition_summary" href="#!" class="collection-item">
                                                        <span class="badge" v-html="_unitHumanReadable(row)"></span>{{ row.name }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col s6">
                                                <div class="collection">
                                                    <a v-for="row in getEnvironmentSummary(entry)" href="#!" class="collection-item">
                                                        <span class="badge" v-html="_unitHumanReadable(row)"></span>{{ row.name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col s12" v-if="currentEntry.state.tab.index === 1">
                                        <div class="collection">
                                            <a v-for="row in entry._shopping_list" href="#!" class="collection-item">
                                                <label>
                                                    <input type="checkbox" class="filled-in" />
                                                    <span>{{ getIngredient(row.id).name }}</span>
                                                </label>
                                                <span class="badge" v-html="_unitHumanReadable(row)"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col s12" v-if="currentEntry.state.tab.index === 2">
                                        <div>
                                            <p>{{ getCurrentStep().description }}</p>
                                            <div class="collection">
                                                <a v-for="ingredient in getCurrentStep().ingredients" href="#!" class="collection-item">
                                                    <span class="badge" v-html="_unitHumanReadable(ingredient)"></span>{{ getIngredient(ingredient.id).name }}
                                                </a>
                                            </div>
                                            <div v-if="getCurrentStep().image">
                                                <img :src="getCurrentStep().image" class="responsive-img recipe-image-border recipe-image" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="rate" class="col s12" v-if="currentEntry.state.tab.index === 3">
                                        <p class="center-align">{{ currentEntry.recipe.finished.description }}</p>
                                        <img :src="currentEntry.recipe.finished.image" class="responsive-img recipe-image-border recipe-image" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" v-if="currentEntry !== null">
                                <a class="waves-effect waves-light btn-flat blue-grey left white-text" @click="previousStep()">Previous</a>
                                <div class="col">
                                    <a class="m2" v-for="alarm in _markers" href="#" @click="setState(alarm.current_step, alarm.current_insider)">
                                        <span class="btn-flat white-text" :class="{ 'blink_me red': alarm.time === 0, 'green': alarm.time > 0 }">
                                            {{ _timeHumanReadable(alarm.time) }} <i class="fa fa-sm fa-clock"></i>
                                        </span>
                                    </a>
                                </div>
                                <a class="waves-effect waves-light btn-flat blue-grey right white-text" @click="nextStep()">Next</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Recipe",
        data: function () {
            return {
                stupid_fix: 0,

                filters: {
                    search_text: '',
                    dietary_preference: -1,
                    season: -1,
                    nutrition: {}
                },
                currentEntry: null,
                nutrition: [
                    {
                        id: 0,
                        name: "Saturated Fat",
                        unit: "NaN",
                    },
                    {
                        id: 1,
                        name: "Polyunsaturated Fat",
                        unit: "NaN",
                    },
                    {
                        id: 2,
                        name: "Monounsaturated Fat",
                        unit: "NaN",
                    },
                    {
                        id: 3,
                        name: "Cholesterol",
                        unit: "NaN",
                    },
                    {
                        id: 4,
                        name: "Sodium",
                        unit: "NaN",
                    },
                    {
                        id: 5,
                        name: "Dietary Fiber",
                        unit: "NaN",
                    },
                    {
                        id: 6,
                        name: "Sugars",
                        unit: "NaN",
                    },
                    {
                        id: 7,
                        name: "Protein",
                        unit: "NaN",
                    },
                    {
                        id: 8,
                        name: "Calcium",
                        unit: "NaN",
                    },
                    {
                        id: 9,
                        name: "Potassium",
                        unit: "NaN",
                    },
                    {
                        id: 11,
                        name: "Iron",
                        unit: "NaN",
                    },
                    {
                        id: 12,
                        name: "Vitamin A",
                        unit: "NaN",
                    },
                    {
                        id: 13,
                        name: "Vitamin B",
                        unit: "NaN",
                    },
                ],
                environment: [
                    {
                        id: 0,
                        name: "Energy",
                        unit: "kWh"
                    },
                    {
                        id: 1,
                        name: "Water",
                        unit: "l"
                    },
                    {
                        id: 2,
                        name: "CO2",
                        unit: "kg"
                    }
                ],
                ingredients: [
                    {
                        "id": 0,
                        "name": "Sweet Potato",
                        "nutrition": [
                            {
                                id: 0,
                                value: 0.0002
                            },
                            {
                                id: 1,
                                value: 0.0001
                            },
                            {
                                id: 2,
                                value: 0.0001
                            },
                            {
                                id: 4,
                                value: 0.00055
                            },
                            {
                                id: 5,
                                value: 0.03
                            },
                            {
                                id: 6,
                                value: 0.0418
                            },
                            {
                                id: 7,
                                value: 0.0157
                            },
                            {
                                id: 8,
                                value: 0.0003
                            },
                            {
                                id: 9,
                                value: 0.00337
                            },
                            {
                                id: 11,
                                value: 0.0006
                            },
                            {
                                id: 12,
                                value: 0.14185
                            },
                            {
                                id: 13,
                                value: 0.000024
                            }
                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 10,
                            },
                            {
                                id: 1,
                                value: 80,
                            },
                            {
                                id: 2,
                                value: 10,
                            }
                        ]
                    },
                    {
                        "id": 1,
                        "name": "Creme double",
                        "nutrition": [
                            {
                                id: 0,
                                value: 0.45
                            },
                            {
                                id: 3,
                                value: 0.001429
                            },
                            {
                                id: 4,
                                value: 0.000357
                            },
                            {
                                id: 6,
                                value: 0.0179
                            },
                            {
                                id: 7,
                                value: 0.0179
                            },
                            {
                                id: 8,
                                value: 0.000714
                            },
                            {
                                id: 12,
                                value: 0.026786
                            },
                            {
                                id: 13,
                                value: 0.000429
                            }
                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 1,
                            },
                            {
                                id: 1,
                                value: 3,
                            },
                            {
                                id: 2,
                                value: 0.5,
                            }
                        ]
                    },
                    {
                        "id": 2,
                        "name": "Vegetable Broth",
                        "nutrition": [
                            {
                                id: 4,
                                value: 0.002292
                            },
                            {
                                id: 7,
                                value: 0.0083
                            }
                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 4,
                            },
                            {
                                id: 1,
                                value: 120,
                            },
                            {
                                id: 2,
                                value: 4,
                            }
                        ]
                    },
                    {
                        "id": 3,
                        "name": "Salt",
                        "nutrition": [
                            {
                                id: 4,
                                value: 0.00038758
                            },
                            {
                                id: 9,
                                value: 0.00008
                            }
                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 0.3,
                            },
                            {
                                id: 1,
                                value: 2,
                            },
                            {
                                id: 2,
                                value: 0.3,
                            }
                        ]
                    },
                    {
                        "id": 4,
                        "name": "Pfeffer",
                        "nutrition": [
                            {
                                id: 0,
                                value: 0.014
                            },
                            {
                                id: 1,
                                value: 0.01
                            },
                            {
                                id: 4,
                                value: 0.0002
                            },
                            {
                                id: 5,
                                value: 0.250
                            },
                            {
                                id: 6,
                                value: 0.006
                            },
                            {
                                id: 9,
                                value: 0.01329
                            },
                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 0.12,
                            },
                            {
                                id: 1,
                                value: 5,
                            },
                            {
                                id: 2,
                                value: 5,
                            }
                        ]
                    },
                    {
                        "id": 5,
                        "name": "Sprigs of Thyme",
                        "environment": [
                            {
                                id: 0,
                                value: 3,
                            },
                            {
                                id: 1,
                                value: 200,
                            },
                            {
                                id: 2,
                                value: 100,
                            }
                        ]
                    },
                    {
                        "id": 6,
                        "name": "Pilze",
                        "nutrition": [
                            {
                                id: 0,
                                value: 0.001
                            },
                            {
                                id: 1,
                                value: 0.002
                            },
                            {
                                id: 4,
                                value: 0.00005
                            },
                            {
                                id: 9,
                                value: 0.00318
                            },
                            {
                                id: 5,
                                value: 0.01
                            },
                            {
                                id: 6,
                                value: 0.02
                            },
                            {
                                id: 7,
                                value: 0.031
                            }
                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 3,
                            },
                            {
                                id: 1,
                                value: 340,
                            },
                            {
                                id: 2,
                                value: 15,
                            }
                        ]
                    },
                    {
                        "id": 7,
                        "name": "Lauch",
                        "nutrition": [
                            {
                                id: 0,
                                value: 0.0004
                            },
                            {
                                id: 1,
                                value: 0.0017
                            },
                            {
                                id: 2,
                                value: 0.0001
                            },
                            {
                                id: 4,
                                value: 0.0002
                            },
                            {
                                id: 5,
                                value: 0.018
                            },
                            {
                                id: 6,
                                value: 0.039
                            },
                            {
                                id: 7,
                                value: 0.015
                            },
                            {
                                id: 8,
                                value: 0.00059
                            },
                            {
                                id: 9,
                                value: 0.0018
                            },
                            {
                                id: 11,
                                value: 0.000021
                            },
                            {
                                id: 12,
                                value: 0.01665
                            },
                            {
                                id: 13,
                                value: 0.00012
                            }
                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 2,
                            },
                            {
                                id: 1,
                                value: 12,
                            },
                            {
                                id: 2,
                                value: 3.4,
                            }
                        ]
                    },
                    {
                        "id": 8,
                        "name": "Olive Oil",
                        "environment": [
                            {
                                id: 0,
                                value: 3,
                            },
                            {
                                id: 1,
                                value: 134,
                            },
                            {
                                id: 2,
                                value: 50,
                            }
                        ]
                    },
                    {
                        "id": 9,
                        "name": "Butter",
                        "nutrition": [
                            {
                                id: 0,
                                value: 0.5137
                            },
                            {
                                id: 1,
                                value: 0.0304
                            },
                            {
                                id: 2,
                                value: 0.2102
                            },
                            {
                                id: 3,
                                value: 0.002150
                            },
                            {
                                id: 4,
                                value: 0.00011
                            },
                            {
                                id: 6,
                                value: 0.0006
                            },
                            {
                                id: 7,
                                value: 0.0085
                            },
                            {
                                id: 8,
                                value: 0.00024
                            },
                            {
                                id: 9,
                                value: 0.00024
                            },
                            {
                                id: 11,
                                value: 0.000001
                            },
                            {
                                id: 12,
                                value: 0.025
                            }
                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 0.4,
                            },
                            {
                                id: 1,
                                value: 80,
                            },
                            {
                                id: 2,
                                value: 10,
                            }
                        ]
                    },
                    {
                        "id": 10,
                        "name": "Parmesan",
                        "nutrition": [
                            {
                                id: 0,
                                value: 0.1641
                            },
                            {
                                id: 1,
                                value: 0.0057
                            },
                            {
                                id: 2,
                                value: 0.0751
                            },
                            {
                                id: 3,
                                value: 0.00068
                            },
                            {
                                id: 4,
                                value: 0.01602
                            },
                            {
                                id: 6,
                                value: 0.008
                            },
                            {
                                id: 7,
                                value: 0.357
                            },
                            {
                                id: 8,
                                value: 0.1184
                            },
                            {
                                id: 9,
                                value: 0.00092
                            },
                            {
                                id: 11,
                                value: 0.000008
                            },
                            {
                                id: 12,
                                value: 0.004
                            },

                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 12.3,
                            },
                            {
                                id: 1,
                                value: 443,
                            },
                            {
                                id: 2,
                                value: 3000,
                            }
                        ]
                    },
                    {
                        id: 11,
                        name: "Whole Wheat Flour",
                        nutrition: [
                            {
                                id: 5,
                                value: 0.1204,
                            },
                            {
                                id: 6,
                                value: 0.015
                            },
                            {
                                id: 7,
                                value: 0.1204
                            },
                            {
                                id: 9,
                                value: 0.003912
                            },
                            {
                                id: 11,
                                value: 0.000032
                            }
                        ],
                        "environment": [
                            {
                                id: 0,
                                value: 4,
                            },
                            {
                                id: 1,
                                value: 34,
                            },
                            {
                                id: 2,
                                value: 2,
                            }
                        ]
                    },
                    {
                        id: 12,
                        name: "Sourdough Starter",
                        nutrition: [
                            {
                                id: 5,
                                value: 0.0602,
                            },
                            {
                                id: 6,
                                value: 0.008
                            },
                            {
                                id: 7,
                                value: 0.0602
                            },
                            {
                                id: 9,
                                value: 0.001965
                            },
                            {
                                id: 11,
                                value: 0.000016
                            }
                        ]
                    },
                    {id: 13, name: "Water"},
                    {
                        id: 14,
                        name: "Nut Mix",
                        nutrition: [
                            {
                                id: 0,
                                value: 0.055
                            },
                            {
                                id: 1,
                                value: 0.25
                            },
                            {
                                id: 5,
                                value: 0.11
                            },
                            {
                                id: 6,
                                value: 0.240
                            },
                            {
                                id: 7,
                                value: 0.19
                            }
                        ],
                    }
                ],
                recipes: [
                    {
                        id: 0,
                        "cover": "images/recipes/0/cover.jpg",
                        properties: {
                            dietary_preference: 2,
                            season: [2,3],
                        },
                        state: {
                            step_counter: 0,
                            current_step: 0,
                            current_insider: 0,
                            tab: {
                                index: 0
                            },
                            alarm: {
                                last_update: 0,
                                list: []
                            }
                        },
                        recipe: {
                            "name": "Süsskartoffel-Auflauf mit Pilzen und Lauch",
                            "finished": {
                                "description": "You did it! - We highly recommend enjoying this food with some high-quality pear juice.",
                                "image": "images/recipes/0/finished.jpg",
                            },
                            "steps": [
                                [
                                    {
                                        "ingredients": [
                                            { "id": 0, "value": 0.6, "unit": "kg" }
                                        ],
                                        "description": "Die Süßkartoffeln schälen, halbieren und in dünne Scheiben schneiden.",
                                        "image": "images/recipes/0/0.jpg"
                                    },
                                    {
                                        "ingredients": [
                                            { "id": 1, "value": 0.4, "unit": "kg" },
                                            { "id": 2, "value": 0.2, "unit": "l" },
                                            { "id": 3, "seasoning": true },
                                            { "id": 4, "seasoning": true },
                                            { "id": 5, "value": 3 }
                                        ],
                                        "description": "Creme double in einen großen Topf mit der Gemüsebrühe aufkochen, mit Salz und Pfeffer würzen und Thymianzweige zugeben.",
                                        "image": "images/recipes/0/1.jpg"
                                    },
                                    {
                                        "ingredients": [],
                                        "description": "Den Topf vom Herd nehmen, die Süßkartoffelscheiben zugeben und mit geschlossenem Deckel in der Flüssigkeit ziehen lassen.",
                                        "marker": {
                                            "notice": true,
                                            "time": 150
                                        },
                                        "image": "images/recipes/0/2.jpg"
                                    }
                                ],
                                [
                                    {
                                        "ingredients": [
                                            { "id": 5, "value": 3 },
                                            { "id": 6, "value": 0.25, "unit": "kg" },
                                            { "id": 7, "value": 0.25, "unit": "kg" }
                                        ],
                                        "description": "Pilze und Lauch putzen und in Scheiben schneiden. Die Blättchen von den übrigen Thymianzweigen abzupfen.",
                                        "image": "images/recipes/0/3.jpg"
                                    },
                                    {
                                        "ingredients": [
                                            { "id": 8, "value": 0.011, "unit": "kg" },
                                            { "id": 9, "value": 0.012, "unit": "kg" },
                                            { "id": 3, "seasoning": true },
                                            { "id": 4, "seasoning": true }
                                        ],
                                        "description": "Olivenöl und Butter in einer großen Pfanne erhitzen. Die Pilze darin ca. 5 Minuten anbraten.",
                                        "marker": {
                                            "time": 120
                                        }
                                    },
                                    {
                                        "ingredients": [
                                            { "id": 3, "seasoning": true },
                                            { "id": 4, "seasoning": true }
                                        ],
                                        "description": "Mit Salz und Pfeffer würzen und den Thymianblättchen und dem Lauch vermengen.",
                                        "image": "images/recipes/0/4.jpg"
                                    }
                                ],
                                [
                                    {
                                        "ingredients": [],
                                        "description": "Den Backofen auf 190°C vorheizen."
                                    },
                                    {
                                        "ingredients": [
                                            { "id": 3, "seasoning": true },
                                            { "id": 4, "seasoning": true },
                                            { "id": 10, "value": 0.08, "unit": "kg" }
                                        ],
                                        "description": "Abwechselnd die Süßkartoffeln mit der Pilz-Lauch-Mischung in eine große Auflafform einschichten. Mit Salz und Pfeffer würzen, die übrige Flüssigkeit darübergießen und mit dem Parmesan bestreuen."
                                    }
                                ],
                                [
                                    {
                                        "ingredients": [],
                                        "description": "Die Form in den Ofen stellen und den Auflag in ca. 30 - 40 Minuten (je nach Dicke der Süßkartoffelscheiben) garen. Wer mag, schaltet zum Schluss die Grullfunktion zu, bs die Oberfläche goldbraun und knusprig ist.",
                                        "marker":{
                                            "time": 1800
                                        }
                                    }
                                ]
                            ]
                        }
                    },
                    {
                        id: 1,
                        "cover": "images/recipes/1/cover.jpg",
                        properties: {
                            dietary_preference: 3,
                            season: [0,1,2,3],
                        },
                        state: {
                            step_counter: 0,
                            current_step: 0,
                            current_insider: 0,
                            tab: {
                                index: 0
                            },
                            alarm: {
                                last_update: 0,
                                list: []
                            }
                        },
                        recipe: {
                            "name": "Sourdough Whole Grain Bread",
                            "finished": {
                                "description": "Perfection! - This type of bread goes with basically everything, and can even be enjoyed on its own.",
                                "image": "images/recipes/0/finished.jpg",
                            },
                            "steps": [
                                [
                                    {
                                        "ingredients": [
                                            { "id": 3, "value": 0.02, "unit": "kg" },
                                            { "id": 11, "value": 0.4, "unit": "kg" },
                                            { "id": 12, "value": 0.2, "unit": "kg" },
                                            { "id": 13, "value": 0.260, "unit": "l" },
                                        ],
                                        "description": "Mix the flour, sourdough starter, tepid water and the salt with a fork as well as you can. Knead the dough on a floured work-surface for 8 - 10 minutes until the window test yields positive results.",
                                        "marker": {
                                            "time":  480
                                        }
                                    },
                                    {
                                        "ingredients": [],
                                        "description": "Place your dough in a floured bowl and let it rest for 3 hours, and cover it with a tea towel.",
                                        "marker": {
                                            "time": 10800
                                        }
                                    },
                                ],
                                [
                                    {
                                        "ingredients": [
                                            { "id": 14, "value": 0.5, "unit": "kg" },
                                        ],
                                        "description": "Put the dough on a floured work-surface and knead in the nut mix.",
                                    },
                                    {
                                        "ingredients": [],
                                        "description": "Form a ball out of your dough and place it in a floured bread basket for an additional 3 hours of reset, and cover it with a tea towel. ",
                                        "marker": {
                                            "time": 10800
                                        }
                                    },
                                    {
                                        "ingredients": [
                                            { "id": 13, "value": 0.15, "unit": "l" }
                                        ],
                                        "description": "Place your dutch oven with its lid in the oven and preheat it to 250°C. Additionally, place a container with some water in the bottom of the oven.",
                                        "marker": {
                                            "time": 7200
                                        }
                                    }
                                ],
                                [
                                    {
                                        "ingredients": [
                                            { "id": 11, "value": 0.1, "unit": "kg" }
                                        ],
                                        "description": "Take the dutch oven out of the oven (be careful, it is extremely hot at this point), and flour the bottom of the pot. Now place your dough in it, and scare the" +
                                            " top of the bread at a 45° angle with a very sharp blade along the top of the bread.",
                                    },
                                    {
                                        "ingredients": [],
                                        "description": "Put the lid back on and place it in the oven for at least 30 minutes.",
                                        "marker": {
                                            time: 1800
                                        }
                                    },
                                    {
                                        "ingredients": [
                                        ],
                                        "description": "Carefully remove the pod of the lid and let it bake for another 15 minutes.",
                                        "marker": {
                                            time: 900
                                        }
                                    }
                                ]
                            ],
                        }
                    }
                ]
            }
        },
        methods: {
            getCurrentStep()
            {
                return this.currentEntry.recipe.steps[this.currentEntry.state.current_step][this.currentEntry.state.current_insider];
            },
            setState(step, insider)
            {
                this.currentEntry.state.current_step = step;
                this.currentEntry.state.current_insider = insider;
            },
            nextStep()
            {
                const recipe = this.getCurrentStep();

                if(this.currentEntry.state.tab.index === 0)
                {
                    this.currentEntry.state.tab.index = 1;
                    return;
                }
                else if(this.currentEntry.state.tab.index === 1)
                {
                    this.currentEntry.state.step_counter++;
                    this.currentEntry.state.tab.index = 2;
                    return;
                }
                else if(this.currentEntry.state.tab.index === 2)
                {
                    if(this.currentEntry.state.current_insider === this.currentEntry.recipe.steps[this.currentEntry.state.current_step].length - 1) {
                        if (this.currentEntry.state.current_step === this.currentEntry.recipe.steps.length - 1) {
                            this.currentEntry.state.step_counter++;
                            this.currentEntry.state.tab.index = 3;
                            return;
                        }
                    }
                }
                else if(this.currentEntry.state.tab.index === 3)
                {
                    return;
                }

                if(recipe.hasOwnProperty('marker'))
                {
                    if(recipe.marker.hasOwnProperty('time'))
                    {
                        if(!this._isTimerActive(this.currentEntry.state.current_step, this.currentEntry.state.current_insider))
                        {
                            this.currentEntry.state.alarm.list.push({
                                'time': recipe.marker.time * 1000,
                                'current_step': this.currentEntry.state.current_step,
                                'current_insider': this.currentEntry.state.current_insider
                            });
                        }
                    }
                }

                if(this.currentEntry.state.current_insider === this.currentEntry.recipe.steps[this.currentEntry.state.current_step].length - 1)
                {
                    if(this.currentEntry.state.current_step === this.currentEntry.recipe.steps.length - 1)
                    {
                        return;
                    }
                    this.currentEntry.state.current_step++;
                    this.currentEntry.state.current_insider = 0;
                }
                else
                {
                    this.currentEntry.state.current_insider++;
                }

                this.currentEntry.state.step_counter++;

            },
            previousStep()
            {

                if(this.currentEntry.state.tab.index === 0)
                {
                    return;
                }
                else if(this.currentEntry.state.tab.index === 1)
                {
                    this.currentEntry.state.tab.index = 0;
                    return;
                }
                else if(this.currentEntry.state.tab.index === 2)
                {
                    if(this.currentEntry.state.current_insider === 0) {
                        if (this.currentEntry.state.current_step === 0) {
                            this.currentEntry.state.step_counter--;
                            this.currentEntry.state.tab.index = 1;
                            return;
                        }
                    }
                }
                else if(this.currentEntry.state.tab.index === 3)
                {
                    this.currentEntry.state.step_counter--;
                    this.currentEntry.state.tab.index = 2;
                    return;
                }

                if(this.currentEntry.state.current_insider === 0)
                {
                    if(this.currentEntry.state.current_step === 0)
                    {
                        return;
                    }
                    this.currentEntry.state.current_step--;
                    this.currentEntry.state.current_insider = this.currentEntry.recipe.steps[this.currentEntry.state.current_step].length - 1;
                }
                else
                {
                    this.currentEntry.state.current_insider--;
                }
                this.currentEntry.state.step_counter--;
            },
            getIngredient(id)
            {
                for(let key in this.ingredients)
                {
                    if(this.ingredients[key].id === id)
                    {
                        return this.ingredients[key];
                    }
                }
                return null;
            },
            getNutrition(id)
            {
                id = parseInt(id);
                for(let key in this.nutrition)
                {
                    if(this.nutrition[key].id === id)
                    {
                        return this.nutrition[key];
                    }
                }
                return null;
            },
            getEnvironment(id)
            {
                id = parseInt(id);
                for(let key in this.environment)
                {
                    if(this.environment[key].id === id)
                    {
                        return this.environment[key];
                    }
                }
                return null;
            },
            getShoppingList(entry)
            {
                const list = {};
                entry.recipe.steps.forEach(function (a) {
                    a.forEach(function (b) {
                        b.ingredients.forEach(function (i) {
                            if(list.hasOwnProperty(i.id))
                            {
                                if(!i.hasOwnProperty('seasoning'))
                                {
                                    list[i.id].value += i.value;
                                }
                            }
                            else
                            {
                                list[i.id] = Object.assign({}, i);
                            }
                        });
                    })
                });

                return list;
            },
            getNutritionSummary(entry)
            {
                const self = this;
                const nutrition = {};
                entry.recipe.steps.forEach(function (a) {
                    a.forEach(function (b) {
                        b.ingredients.forEach(function (i) {
                            let ingredient = self.getIngredient(i.id);
                            if((typeof i.seasoning === 'undefined' || i.seasoning === false) && ingredient.hasOwnProperty('nutrition'))
                            {
                                ingredient.nutrition.forEach(function (n) {
                                    nutrition[n.id] = n.value * i.value;
                                });
                            }
                        });
                    })
                });

                const summary = {};
                for(let key in nutrition)
                {
                    let result = this.__convertUnits(nutrition[key], 'kg');
                    summary[key] = { name: this.getNutrition(key).name, value: result.value, unit: result.unit, _raw_kg_value: nutrition[key], nutrition_id: key };
                }

                return summary;
            },
            getEnvironmentSummary(entry)
            {
                const self = this;
                const environment = {};
                entry.recipe.steps.forEach(function (a) {
                    a.forEach(function (b) {
                        b.ingredients.forEach(function (i) {
                            let ingredient = self.getIngredient(i.id);
                            if((typeof i.seasoning === 'undefined' || i.seasoning === false) && ingredient.hasOwnProperty('environment'))
                            {
                                ingredient.environment.forEach(function (n) {
                                    environment[n.id] = n.value * i.value;
                                });
                            }
                        });
                    })
                });

                const summary = {};
                for(let key in environment)
                {
                    let env = this.getEnvironment(key);
                    summary[key] = { name: env.name, value: environment[key], unit: env.unit };
                }

                return summary;
            },
            _isTimerActive(step, insider)
            {
                for(let key in this.currentEntry.state.alarm.list)
                {
                    const value = this.currentEntry.state.alarm.list[key];

                    if(value.current_step === step && value.current_insider === insider)
                    {
                        return true;
                    }
                }
                return false;
            },
            _timeHumanReadable(ms)
            {
                let time = ms / 1000;
                let seconds = ~~(time % 60);
                let minutes = ~~((time - seconds) / 60);

                return (minutes + ":" + seconds);
            },
            _unitHumanReadable(ingredient)
            {
                if(ingredient.hasOwnProperty('value') && ingredient.hasOwnProperty('unit'))
                {
                    let result = this.__convertUnits(ingredient.value, ingredient.unit);
                    return (result.value + result.unit);
                }
                else if(ingredient.hasOwnProperty('value'))
                {
                    return (ingredient.value);
                }
                else if(ingredient.hasOwnProperty('seasoning'))
                {
                    return '<i class="fa fa-pepper-hot"></i>'
                }
            },
            _openNutritionModal()
            {
                let instance = window.M.Modal.getInstance(document.getElementById('nutrition-modal'));
                instance.open();
            },
            _openModal(entry)
            {
                this.currentEntry = entry;
                {
                    let instance = window.M.Modal.getInstance(document.getElementById('modal' + entry.id));
                    instance.open();
                }
            },
            __convertUnits(value, unit, _to = null)
            {
                let weightUnits = ['kg', 'g', 'mg'];
                let volumeUnits = ['l', 'ml'];

                let unitIndex = 0;
                let units = [];

                let tmp = 0;
                if((tmp = weightUnits.indexOf(unit)) !== -1)
                {
                    unitIndex = tmp;
                    units = weightUnits;
                }
                else if((tmp = volumeUnits.indexOf(unit)) !== -1)
                {
                    unitIndex = tmp;
                    units = volumeUnits;
                }

                if(_to === null)
                {
                    while (value < 1 && unitIndex < units.length - 1)
                    {
                        unitIndex++;
                        value *= 1000;
                        unit = units[unitIndex];
                    }
                }
                else
                {
                    let from_index = unitIndex;
                    let to_index = units.indexOf(_to);

                    if(from_index < to_index)
                    {
                        while (from_index < to_index)
                        {
                            from_index++;
                            value *= 1000;
                            unit = units[from_index];
                        }
                    }
                    else if(from_index > to_index)
                    {
                        while (from_index > to_index)
                        {
                            from_index--;
                            value /= 1000;
                            unit = units[from_index];
                        }
                    }
                }
                return { value: parseFloat(value.toFixed(1)), unit: unit };
            },
        },
        mounted() {
            const self = this;

            {
                window.M.Collapsible.init(document.querySelectorAll('.collapsible'), {});
            }

            //
            {
                window.M.Modal.init(document.querySelectorAll('.modal'), {});
            }

            {
                window.M.FormSelect.init(document.querySelectorAll('select'), {});
            }

            {
                let nutrition_ranges = [];
                this.recipes.forEach(function (recipe, key) {
                    let summary = self.getNutritionSummary(recipe);
                    self.recipes[key]._nutrition_summary = summary;
                    self.recipes[key]._shopping_list = self.getShoppingList(recipe);
                    for(let key in Object.keys(summary))
                    {
                        if(typeof summary[key] === 'undefined')
                        {
                            continue;
                        }
                        if(typeof nutrition_ranges[key] === 'undefined')
                        {
                            nutrition_ranges[key] = [Number.MAX_VALUE, Number.MIN_VALUE];
                        }
                        nutrition_ranges[key][0] = Math.min(summary[key]._raw_kg_value, nutrition_ranges[key][0]);
                        nutrition_ranges[key][1] = Math.max(summary[key]._raw_kg_value, nutrition_ranges[key][1]);

                    }
                });

                nutrition_ranges.forEach(function (value, key) {
                    let min_result = self.__convertUnits(value[0], 'kg');
                    let max_result = self.__convertUnits(value[1], 'kg', min_result.unit);



                    nutrition_ranges[key][0] = min_result.value;
                    nutrition_ranges[key][1] = max_result.value;
                    nutrition_ranges[key][2] = min_result.unit;

                });
                console.log(nutrition_ranges);

                this.nutrition.forEach(function (nutrition, key) {
                    if(typeof nutrition_ranges[nutrition.id] === 'undefined')
                    {
                        return;
                    }

                    if(nutrition_ranges[nutrition.id][0] === nutrition_ranges[nutrition.id][1])
                    {
                        nutrition_ranges[nutrition.id][0] *= 0.2;
                        nutrition_ranges[nutrition.id][1] *= 1.2;
                    }

                    self.nutrition[key].unit = nutrition_ranges[nutrition.id][2];
                    let slider = document.getElementById('nutrition-slider-' + nutrition.id);
                    window.noUiSlider.create(slider, {
                        start: [nutrition_ranges[nutrition.id][0], nutrition_ranges[nutrition.id][1]],
                        connect: true,
                        step: 0.1,
                        orientation: 'horizontal', // 'horizontal' or 'vertical'
                        range: {
                            'min': nutrition_ranges[nutrition.id][0],
                            'max': nutrition_ranges[nutrition.id][1]
                        },
                        format: wNumb({
                            decimals: 1
                        }),
                        // Show a scale with the slider
                    });
                    slider.noUiSlider.on('set', function( values, handle ) {
                        self.filters.nutrition[nutrition.id] = [parseFloat(values[0]), parseFloat(values[1])];
                        self.stupid_fix++;
                    });

                });
            }

            let x = setInterval(function() {
                self.recipes.forEach(function (entry) {
                    let last_update = new Date().getTime();

                    let now = new Date().getTime();
                    // Get today's date and time
                    let delta = now - entry.state.alarm.last_update;

                    var removeAlarms = [];
                    for(let key in entry.state.alarm.list)
                    {
                        if(entry.state.alarm.list[key].time <= 1)
                        {
                            entry.state.alarm.list[key].time = 0;
                        }
                        else
                        {
                            entry.state.alarm.list[key].time -= delta;
                        }
                    }

                    for(let key in removeAlarms)
                    {
                        entry.state.alarm.list.splice(key, 1);
                    }

                    entry.state.alarm.last_update = now;
                })

            }, 1000);
        },
        computed: {
            _current_recipe_progress() {
                if(this.currentEntry === null)
                {
                    return 0;
                }

                let counter = 1;
                this.currentEntry.recipe.steps.forEach((function (value) {
                    counter += value.length;
                }));
                return (this.currentEntry.state.step_counter / counter) * 100;
            },
            _markers() {
                if(this.currentEntry === null)
                {
                    return [];
                }
                this.currentEntry.state.alarm.list.sort(function compare(a, b) {
                    if (a.time > b.time) return -1;
                    if (b.time > a.time) return 1;

                    return 0;
                });
                return this.currentEntry.state.alarm.list;
            },
            _filtered_recipes: function () {
                const self = this;
                this.stupid_fix--;

                return this.recipes.filter(function (value) {
                    if(self.filters.search_text.trim() !== '' && value.recipe.name.toLowerCase().indexOf(self.filters.search_text.toLowerCase()) === -1)
                    {
                        return false;
                    }

                    let f = 1;
                    f &= (value.properties.dietary_preference >= parseInt(self.filters.dietary_preference));
                    f &= (self.filters.season === -1 || value.properties.season.includes(parseInt(self.filters.season)));

                    for(let key in self.filters.nutrition)
                    {
                        if(self.filters.nutrition.hasOwnProperty(key) && value._nutrition_summary.hasOwnProperty(key))
                        {
                            f &= (value._nutrition_summary[key].value >= self.filters.nutrition[key][0] && value._nutrition_summary[key].value <= self.filters.nutrition[key][1]);
                            if(f === 0)
                            {
                                break;
                            }
                        }
                    }

                    return f === 1;
                });
            }
        }
    }
</script>
