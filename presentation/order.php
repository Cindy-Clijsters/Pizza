<!DOCTYPE html>
<html lang="nl">
    <head>
        <title>Pizza's</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="assets/css/normalize.css">
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    </head>
    <body>
        
        {{ include('includes/header.php') }}
        
        <main>

            <div class="wrapper cf">

                <form method="post" action="order.php" novalidate>

                    <section id="my-order">

                        <div class="align-right"><a href="menu.php">Verder winkelen</a></div>
                        
                        <h1>Mijn bestelling</h1>

                        <h2>Mijn winkelmandje</h2>

                        {% if shoppingCartInfo.orderLines is empty %}

                            <p>Er zijn nog geen pizza's toevoegd aan je winkelmandje.</p>

                        {% else %}

                            <table>
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Eenheidsprijs</th>
                                        <th>Aantal</th>
                                        <th class="number">Prijs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {% for orderLine in shoppingCartInfo.orderLines %}
                                    <tr>
                                        <td><img src="assets/images/{{ orderLine.pizza.image }}" class="pizzaImage" alt="{{ orderLine.pizza.name }}" title="{{ orderLine.pizza.name }}" /></td>
                                        <td><strong>{{ orderLine.pizza.name }}</strong> {% if orderLine.pizza.vegetarian == true %} <span class="vegetarian">(Vegetarisch)</span> {% endif %}<br>
                                            {{ orderLine.pizza.description}}</td>
                                        <td class="number">
                                            {% if user.promotions is defined and user.promotions == true %}
                                                {{ orderLine.pizza.adjustedPromotionPrice|number_format(2, ',', ' ') }}&nbsp;&euro;
                                            {% else %}
                                                {{ orderLine.pizza.price|number_format(2, ',', ' ') }}&nbsp;&euro;
                                            {% endif %}    
                                        </td>
                                        <td>
                                            <a href="updateOrder.php?pizza={{ orderLine.pizza.id }}&action=reduce&step=order"><img src="assets/images/minus.png" class="addMinImage" alt="Minus" title="Minus" /></a>
                                            {{ orderLine.amount }}
                                            <a href="updateOrder.php?pizza={{ orderLine.pizza.id }}&action=increase&step=order"><img src="assets/images/plus.png" class="addMinImage" alt="Plus" title="Plus" /></a>
                                        </td>
                                        <td class="number">{{ orderLine.totalPrice|number_format(2, ',', ' ') }}&nbsp;&euro; </td>
                                    </tr>
                                    {% endfor %}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="number">Totaal: </th>
                                        <th class="number">{{ shoppingCartInfo.totalPrice|number_format(2, ',', ' ') }}&nbsp;&euro;</th>
                                    </tr>
                                </tfoot>
                            </table>

                            

                        {% endif %}

                    </section>

                    <section id="delivery-time">

                        <h2>Tijdstip van levering</h2>

                        <label for="delivery-date">Datum van levering *</label><br>
                        <input type="date" name="delivery-date" id="delivery-date" value="{{ deliveryDate }}"  class="input-small"><br>
                        {% if errors.deliveryDate is defined %}
                            <div class="error">{{ errors.deliveryDate }}</div>
                        {% endif %}                
                        <br>

                        <label for="delivery-time">Tijdstip van levering *</label><br>
                        <input type="time" name="delivery-time" id="delivery-time" value="{{ deliveryTime }}"  class="input-small"><br>
                        {% if errors.deliveryTime is defined %}
                            <div class="error">{{ errors.deliveryTime}}</div>
                        {% endif %}                

                    </section>

                    <section id="delivery-address">

                        <h2>Leveringsadres</h2>

                        <label for="lastname">Achternaam *</label><br>
                        <input type="text" id="lastname" name="lastname" value="{{ deliveryAddress.lastname }}" maxlength="50" class="input-medium"><br>
                        {% if errors.lastname is defined %}
                            <div class="error">{{ errors.lastname}}</div>
                        {% endif %}
                        <br>

                        <label for="firstname">Voornaam *</label><br>
                        <input type="text" id="firstname" name="firstname" value="{{ deliveryAddress.firstname }}" maxlength="50"  class="input-medium"><br>
                        {% if errors.firstname is defined %}
                            <div class="error">{{ errors.firstname}}</div>
                        {% endif %}
                        <br> 

                        <label for="address">Adres * </label><br>
                        <input type="text" id="address" name="address" value="{{ deliveryAddress.address }}" maxlength="100" class="input-medium"><br>
                        {% if errors.address is defined %}
                            <div class="error">{{ errors.address}}</div>
                        {% endif %}
                        <br>

                        <label for="city">Postcode en gemeente *</label><br>
                        <select id="city" name="city"  class="input-medium">
                            <option value=""></option>
                            {% for city in cities %}
                                <option value="{{ city.id }}" {% if (deliveryAddress.city == city.id) %} selected {% endif %}>{{ city.zipcode }} {{ city.name }}</option>
                            {% endfor %}
                        </select><br>
                        {% if errors.city is defined %}
                            <div class="error">{{ errors.city}}</div>
                        {% endif %}
                        <br>

                        <label for="phone">Telefoon *</label><br>
                        <input type="text" id="phone" name="phone" value="{{ deliveryAddress.phone }}" maxlength="25"  class="input-medium"><br>
                        {% if errors.phone is defined %}
                            <div class="error">{{ errors.phone}}</div>
                        {% endif %}

                    </section>

                    <section id="remarks">

                        <h2>Overige opmerkingen</h2>

                        <label for="remarks">Opmerkingen *</label><br>
                        <textarea name="remarks" id="remarks" cols="100" rows="10" maxlength="2000" class="input-large">{{ remarks }}</textarea><br>
                        {% if errors.remarks is defined %}
                            <div class="error">{{ errors.remarks}}</div>
                        {% endif %}

                    </section>

                    <input type="submit" value="Bestelling plaatsen"> &bullet; <a href="updateOrder.php?action=cancelOrder">Bestelling annuleren</a>

                    <br><br>

                </form>    
            
            </div>
        
        </main>

        
    </body>
</html>
