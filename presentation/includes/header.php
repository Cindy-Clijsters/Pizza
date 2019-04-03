<header>
    <section id="header-login">
        <div class="wrapper cf">
            {% if user is empty %}
                <p><a href="loginOrRegister.php?redirect=menu">Inloggen</a></p>
            {% else %}
                <p>U bent ingelogd als {{ user.firstname }} {{ user.lastname }} &bullet; <a href="logout.php">Uitloggen</a></p>
            {% endif %}                    
        </div>
    </section>

    <section id="menu" class="border-bottom-gray">
        <div class="wrapper cf">
            <div id="logo">
                <a href="menu.php">{{ company.name }}</a>
            </div>
        </div>
    </section>

</header>