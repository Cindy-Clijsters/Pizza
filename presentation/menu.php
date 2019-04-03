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
                <h1>Menu</h1>
                                    
                <section id="pizza-menu">

                    <h2>Pizza's</h2>

                    {% if pizzas is empty %}

                        <p>Er zijn nog geen pizza's beschikbaar.</p>

                    {% else %}

                        <table>
                            {% for pizza in pizzas %}
                                <tr>
                                    <td><img src="assets/images/{{ pizza.image }}" class="pizzaImage" alt="{{ pizza.name }}" title="{{ pizza.name }}" /></td>
                                    <td><strong>{{ pizza.name }}</strong> {% if pizza.vegetarian == true %} <span class="vegetarian">(Vegetarisch)</span> {% endif %}<br>
                                        {{ pizza.description}}</td>
                                    <td class="number">
                                        {% if user.promotions is defined and user.promotions == true %}
                                            {{ pizza.adjustedPromotionPrice|number_format(2, ',', ' ') }}&nbsp;&euro;
                                        {% else %}
                                            {{ pizza.price|number_format(2, ',', ' ') }}&nbsp;&euro;
                                        {% endif %}
                                    </td>
                                    <td>
                                        <form method="post" action="updateOrder.php?pizza={{ pizza.id }}&action=add">
                                            <select name="amount" id="amount-{{ pizza.id }}">
                                                {% for amount in amountRange %}
                                                    <option value="{{ amount }}">{{ amount }}</option>
                                                {% endfor %}
                                            </select>
                                            <input type="submit" value="Bestellen">
                                        </form>
                                    </td>
                                </tr>
                            {% endfor %}
                        </table>

                    {% endif %}

                </section>

                <section id="shopping-basket">
                    <h2>Mijn winkelmandje</h2>

                    {% if shoppingCartInfo.orderLines is empty %}

                        <p>Er zijn geen pizza's toegevoegd in je winkelmandje.</p>

                    {% else %}

                        <table>
                            <thead>
                                <tr>
                                    <th>Naam</th>
                                    <th>Aantal</th>
                                    <th class="number">Prijs</th>
                                </tr>
                            </thead>
                            <tbody>
                                {% for orderLine in shoppingCartInfo.orderLines %}
                                    <tr>
                                        <td>{{ orderLine.pizza.name }}</td>
                                        <td><a href="updateOrder.php?pizza={{ orderLine.pizza.id }}&action=reduce"><img src="assets/images/minus.png" class="addMinImage" alt="Minus" title="Minus" /></a>
                                            {{ orderLine.amount }} 
                                            <a href="updateOrder.php?pizza={{ orderLine.pizza.id }}&action=increase"><img src="assets/images/plus.png" class="addMinImage" alt="Plus" title="Plus" /></a>
                                        </td>
                                        <td class="number">{{ orderLine.totalPrice|number_format(2, ',', ' ') }}&nbsp;&euro; </td>
                                    </tr>
                                {% endfor %}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan='2'>Totaal</td>
                                    <td>{{ shoppingCartInfo.totalPrice|number_format(2, ',', ' ') }}&nbsp;&euro;</td>
                                </tr>
                            </tfoot>
                        </table>

                        <br>

                        <a href="loginOrRegister.php?redirect=order">Afrekenen</a> &bullet; <a href="updateOrder.php?action=cancelOrder">Bestelling annuleren</a>

                    {% endif %}

                </section>
            </div>

        </main>
        
    </body>
</html>

    