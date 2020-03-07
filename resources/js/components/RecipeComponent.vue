<template>
    <div>
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo">Logo</a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="sass.html">Sass</a></li>
                    <li><a href="badges.html">Components</a></li>
                    <li><a href="collapsible.html">JavaScript</a></li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col offset-m3 m6">
                    <div class="input-field col s12 m6">
                        <select class="icons">
                            <option value="" disabled selected>Choose your option</option>
                            <option value="" data-icon="images/sample-1.jpg">Omnivore</option>
                            <option value="" data-icon="images/office.jpg">Pescetarian</option>
                            <option value="" data-icon="images/yuna.jpg">Vegetarian</option>
                            <option value="" data-icon="images/yuna.jpg"></option>
                        </select>
                        <label>Images in select</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6">
                    <div v-for="entry in recipes" class="card">
                        <div class="card-image" v-if="entry.state.card.mode === 0">
                            <img src="https://static01.nyt.com/images/2017/11/08/dining/08COOKING-POTATO2/08COOKING-POTATO2-articleLarge-v2.jpg">
                            <span class="card-title">{{ entry.recipe.name }}</span>
                            <a class="btn-floating halfway-fab waves-effect waves-light red"><i class="fa fa-plus"></i></a>
                        </div>
                        <div class="card-content" v-else-if="entry.state.card.mode === 1">
                            <table>
                                <tbody>
                                    <tr v-for="row in getNutritionSummary(entry)">
                                        <td>{{ row.name }}</td>
                                        <td>{{ row.value + row.unit }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-action">
                            <div class="row">
                                <i class="col s2 fa fa-leaf green-text"></i>
                                <a class="waves-effect waves-light btn right-aligned" @click="_openModal(entry)">
                                    <i class="fa fa-cookie"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Modal Structure -->
                        <div :id="'modal' + entry.id" class="modal modal-fixed-footer">
                            <div class="modal-content" v-if="currentEntry !== null">
                                <p>{{ getCurrentStep().description }}</p>
                                <div class="collection">
                                    <a v-for="ingredient in getCurrentStep().ingredients" href="#!" class="collection-item">
                                        <span class="badge" v-html="_unitHumanReadable(ingredient)"></span>{{ getIngredient(ingredient.id).name }}
                                    </a>
                                </div>
                            </div>
                            <div class="modal-footer" v-if="currentEntry !== null">
                                <a class="waves-effect waves-green btn-flat green left" @click="previousStep()">Previous</a>
                                <div class="col">
                                    <a  class="m2" v-for="alarm in _markers" href="#" @click="setState(alarm.current_step, alarm.current_insider)">
                                <span class="btn-flat white-text" :class="{ 'blink_me red': alarm.time === 0, 'green': alarm.time > 0 }">
                                    {{ _timeHumanReadable(alarm.time) }} <i class="fa fa-sm fa-clock"></i>
                                </span>
                                    </a>
                                </div>
                                <a class="waves-effect waves-green btn-flat green right" @click="nextStep()">Next</a>
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
                currentEntry: null,
                nutrition: [
                    {
                        id: 0,
                        name: "Saturated Fat"
                    },
                    {
                        id: 1,
                        name: "Polyunsaturated Fat",
                    },
                    {
                        id: 2,
                        name: "Monounsaturated Fat"
                    },
                    {
                        id: 3,
                        name: "Cholesterol",
                    },
                    {
                        id: 4,
                        name: "Sodium"
                    },
                    {
                        id: 5,
                        name: "Dietary Fiber"
                    },
                    {
                        id: 6,
                        name: "Sugars"
                    },
                    {
                        id: 7,
                        name: "Protein"
                    },
                    {
                        id: 8,
                        name: "Calcium"
                    },
                    {
                        id: 9,
                        name: "Potassium"
                    },
                    {
                        id: 10,
                        name: "Alcohol"
                    },
                    {
                        id: 11,
                        name: "Iron"
                    },
                    {
                        id: 12,
                        name: "Vitamin A"
                    },
                    {
                        id: 13,
                        name: "Vitamin C"
                    },
                    {
                        id: 14,
                        name: "Caffeine"
                    }
                ],
                ingredients: [
                    {
                        "id": 0,
                        "name": "Süßkartoffeln",
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
                        ]
                    },
                    {
                        "id": 1,
                        "name": "Creme double",
                        "nutrition": [
                            {
                                id: 0,
                                value: 450
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
                        ]
                    },
                    {
                        "id": 2,
                        "name": "Gemüsebrühe",
                        "nutrition": [
                            {
                                id: 4,
                                value: 0.002292
                            },
                            {
                                id: 7,
                                value: 0.0083
                            }
                        ]
                    },
                    {
                        "id": 3,
                        "name": "Salz",
                        "nutrition": [
                            {
                                id: 4,
                                value: 0.00038758
                            },
                            {
                                id: 9,
                                value: 0.00008
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
                            }
                        ]
                    },
                    {
                        "id": 5,
                        "name": "Thymian Zweige"
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
                        ]
                    },
                    {
                        "id": 8,
                        "name": "Olivenöl"
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
                        ]
                    },
                    {
                        "id": 10,
                        "name": "Parmesan",
                        "nutrition": [
                            {
                                id: 0,
                                value: 164.1
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
                                value: 357.5
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
                            }
                        ]
                    }
                ],
                recipes: [
                    {
                        id: 0,
                        state: {
                            current_step: 0,
                            current_insider: 0,
                            alarm: {
                                last_update: 0,
                                list: []
                            },
                            card: {
                                mode: 1
                            }
                        },
                        recipe: {
                            "name": "Süsskartofeel-Auflauf mit Pilzen und Lauch",
                            "properties": {
                                "vegan": true
                            },
                            "steps": [
                                [
                                    {
                                        "ingredients": [
                                            { "id": 1, "value": 0.4, "unit": "kg" },
                                            { "id": 2, "value": 0.2, "unit": "l" },
                                            { "id": 3, "seasoning": true },
                                            { "id": 4, "seasoning": true },
                                            { "id": 5, "value": 3 }
                                        ],
                                        "description": "Creme double in einen großen Topf mit der Gemüsebrühe aufkochen, mit Salz und Pfeffer würzen und Thymianzweige zugeben."
                                    },
                                    {
                                        "ingredients": [
                                            { "id": 0, "value": 0.6, "unit": "kg" }
                                        ],
                                        "description": "Die Süßkartoffeln schälen, halbieren und in dünne Scheiben schneiden."
                                    },
                                    {
                                        "ingredients": [],
                                        "description": "Den Topf vom Herd nehmen, die Süßkartoffelscheiben zugeben und mit geschlossenem Deckel in der Flüssigkeit ziehen lassen.",
                                        "marker": {
                                            "notice": true,
                                            "time": 150
                                        }
                                    }
                                ],
                                [
                                    {
                                        "ingredients": [
                                            { "id": 5, "value": 3 },
                                            { "id": 6, "value": 0.25, "unit": "kg" },
                                            { "id": 7, "value": 0.25, "unit": "kg" }
                                        ],
                                        "description": "Pilze und Lauch putzen und in Scheiben schneiden. Die Blättchen von den übrigen Thymianzweigen abzupfen."
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
                                        "description": "Mit Salz und Pfeffer würzen und mit den Thymianblättchen und den Thymianblättchen und dem Lauch vermengen."
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
            },
            previousStep()
            {
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
                    summary[key] = { name: this.getNutrition(key).name, value: result.value, unit: result.unit };
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
            _openModal(entry)
            {
                this.currentEntry = entry;
                let instance = window.M.Modal.getInstance(document.getElementById('modal0'));
                instance.open();
            },
            __convertUnits(value, unit)
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

                while (value < 1 && unitIndex < units.length)
                {
                    unitIndex++;
                    value *= 1000;
                    unit = units[unitIndex];
                }
                return { value: parseFloat(value.toFixed(1)), unit: unit };
            },
        },
        mounted() {
            const self = this;

            //
            {
                window.M.Modal.init(document.querySelectorAll('.modal'), {});
            }

            {
                window.M.FormSelect.init(document.querySelectorAll('select'), {});
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
            }
        }
    }
</script>
