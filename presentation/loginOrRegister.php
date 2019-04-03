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
                <section id="login">

                    <h2>Ik heb al een account</h2>

                    <form action="loginOrRegister.php?action=login&redirect={{ redirect }}" method="post">
                        <label for="l-mail">E-mail *</label><br>
                        <input type="text" id="l-mail" name="l-mail" value="{{ tmpUser.mail }}" maxlength="100" class="input-medium"><br>
                        {% if action == 'login' and errors.mail is defined %}
                            <div class="error">{{ errors.mail}}</div>
                        {% endif %}
                        <br>

                        <label for="l-password">Wachtwoord *</label><br>
                        <input type="password" id="l-password" name="l-password" value="" maxlength="50" class="input-medium"><br>
                        {% if action == 'login' and errors.password is defined %}
                            <div class="error">{{ errors.password}}</div>
                        {% endif %}
                        <br>

                        <input type="submit" value="Login"/>
                    </form>

                </section>

                <section id="register">

                    <form action="loginOrRegister.php?action=register&redirect={{ redirect }}" method="post">

                        <h2>Ik heb nog geen account</h2>

                        <label for="r-lastname">Achternaam *</label><br>
                        <input type="text" id="r-lastname" name="r-lastname" value="{{ tmpNewUser.lastname }}" maxlength="50" class="input-medium"><br>
                        {% if action == 'register' and errors.lastname is defined %}
                            <div class="error">{{ errors.lastname}}</div>
                        {% endif %}
                        <br>

                        <label for="r-firstname">Voornaam *</label><br>
                        <input type="text" id="r-firstname" name="r-firstname" value="{{ tmpNewUser.firstname }}" maxlength="50" class="input-medium"><br>
                        {% if action == 'register' and errors.firstname is defined %}
                            <div class="error">{{ errors.firstname}}</div>
                        {% endif %}
                        <br> 

                        <label for="r-address">Adres * </label><br>
                        <input type="text" id="r-address" name="r-address" value="{{ tmpNewUser.address }}" maxlength="100" class="input-medium"><br>
                        {% if action == 'register' and errors.address is defined %}
                            <div class="error">{{ errors.address}}</div>
                        {% endif %}
                        <br>

                        <label for="r-city">Postcode en gemeente *</label><br>
                        <select id="r-city" name="r-city" class="input-medium">
                            <option value=""></option>
                            {% for city in cities %}
                                <option value="{{ city.id }}" {% if (tmpNewUser.city == city.id) %} selected {% endif %}>{{ city.zipcode }} {{ city.name }}</option>
                            {% endfor %}
                        </select><br>
                        {% if action == 'register' and errors.city is defined %}
                            <div class="error">{{ errors.city}}</div>
                        {% endif %}
                        <br>

                        <label for="r-phone">Telefoon *</label><br>
                        <input type="text" id="r-phone" name="r-phone" value="{{ tmpNewUser.phone }}" maxlength="25" class="input-medium"><br>
                        {% if action == 'register' and errors.phone is defined %}
                            <div class="error">{{ errors.phone}}</div>
                        {% endif %}
                        <br>

                        <input type="checkbox" name="r-create-account" id="r-create-account" value="1" {% if (tmpNewUser.createAccount == 1) %} checked {% endif %}>
                        <label for="r-create-account">Ik wens een account aan te maken</label><br><br>

                        <label for="r-mail">E-mail *</label><br>
                        <input type="text" id="r-mail" name="r-mail" value="{{ tmpNewUser.mail }}" maxlength="100" class="input-medium"><br>
                        {% if action == 'register' and errors.mail is defined %}
                            <div class="error">{{ errors.mail}}</div>
                        {% endif %}
                        <br>

                        <label for="r-password">Wachtwoord *</label><br>
                        <input type="password" id="r-password" name="r-password" value="" maxlength="50" class="input-medium"><br>
                        {% if action == 'register' and errors.password is defined %}
                            <div class="error">{{ errors.password}}</div>
                        {% endif %}
                        <br>

                        <label for="r-repeat-password">Herhaal wachtwoord *</label><br>
                        <input type="password" id="r-repeat-password" name="r-repeat-password" maxlength="50" value="" class="input-medium"><br>
                        {% if action == 'register' and errors.repeatPassword is defined %}
                            <div class="error">{{ errors.repeatPassword}}</div>
                        {% endif %}
                        <br>

                        <input type="submit" value="Volgende"/>

                    </form>

                </section>
            </div>
        
        </main>
        
    </body>
</html>
