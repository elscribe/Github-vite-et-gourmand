<div class="menu-filter-overlay" id="menu-filter-overlay" data-filter-overlay hidden>
    <div class="menu-filter-overlay-backdrop" data-filter-overlay-close></div>
    <section class="menu-filter-dialog" role="dialog" aria-modal="true" aria-labelledby="menu-filter-dialog-title">
        <header class="menu-filter-dialog-header">
            <div>
                <h2 id="menu-filter-dialog-title">Filtres</h2>
                <p>Vite &amp; Gourmand Bordeaux</p>
            </div>
            <button class="menu-filter-dialog-close" type="button" data-filter-overlay-close aria-label="Fermer les filtres">
                &times;
            </button>
        </header>

        <form class="menu-filter-dialog-form" data-filter-overlay-form>
            <input type="hidden" data-advanced-filter="minPrice">
            <input type="hidden" data-advanced-filter="theme">
            <input type="hidden" data-advanced-filter="regime">
            <input type="hidden" data-advanced-filter="peopleMin">
            <input type="hidden" data-advanced-filter="peopleMax">
            <input type="hidden" data-advanced-filter="availability">
            <input type="hidden" data-advanced-filter="allergens">
            <input type="hidden" data-advanced-filter="seafood">

            <p class="menu-filter-scroll-summary" aria-hidden="true">
                ↑ Budget, Prix max., Thème, Régime (voir plus haut)
            </p>

            <div class="menu-filter-dialog-grid">
                <div class="menu-filter-dialog-column">
                    <fieldset class="menu-filter-group">
                        <legend>Budget</legend>
                        <div class="menu-filter-choice-grid">
                            <button class="menu-filter-choice" type="button" data-filter-chip="budget" data-min-price="" data-max-price="100">Moins de 100 €</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="budget" data-min-price="100" data-max-price="150">100 € à 150 €</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="budget" data-min-price="150" data-max-price="200">150 € à 200 €</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="budget" data-min-price="200" data-max-price="">Plus de 200 €</button>
                        </div>
                    </fieldset>

                    <div class="menu-filter-group">
                        <label for="overlay-max-price">Prix maximum</label>
                        <input id="overlay-max-price" name="max_price" type="number" min="0" step="1" inputmode="numeric" placeholder="200 €" data-advanced-filter="maxPrice">
                        <p>Entrez un prix maximum en euros</p>
                    </div>

                    <fieldset class="menu-filter-group">
                        <legend>Thème</legend>
                        <div class="menu-filter-choice-grid">
                            <button class="menu-filter-choice" type="button" data-filter-chip="theme" data-value="1">Noël / fêtes</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="theme" data-value="6">Cocktail / événement</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="theme" data-value="4">Végétarien / convivial</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="theme" data-value="3">Terre &amp; Mer</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="theme" data-value="2">Saint-Valentin</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="theme" data-value="5">Pâques / famille</button>
                        </div>
                    </fieldset>

                    <fieldset class="menu-filter-group">
                        <legend>Régime</legend>
                        <div class="menu-filter-choice-grid">
                            <button class="menu-filter-choice" type="button" data-filter-chip="regime" data-value="1">Classique</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="regime" data-value="2">Végétarien</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="seafood" data-value="1">Poisson / fruits de mer</button>
                        </div>
                    </fieldset>
                </div>

                <div class="menu-filter-dialog-column">
                    <fieldset class="menu-filter-group">
                        <legend>Nombre de personnes</legend>
                        <div class="menu-filter-choice-grid">
                            <button class="menu-filter-choice" type="button" data-filter-chip="people" data-min-people="2" data-max-people="2">2 personnes</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="people" data-min-people="4" data-max-people="6">4 à 6 personnes</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="people" data-min-people="8" data-max-people="10">8 à 10 personnes</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="people" data-min-people="11" data-max-people="">Plus de 10 personnes</button>
                        </div>
                    </fieldset>

                    <fieldset class="menu-filter-group">
                        <legend>Disponibilité</legend>
                        <div class="menu-filter-choice-grid">
                            <button class="menu-filter-choice" type="button" data-filter-chip="availability" data-value="available">Disponible</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="availability" data-value="limited">Stock limité</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="availability" data-value="week">Disponible cette semaine</button>
                        </div>
                    </fieldset>

                    <fieldset class="menu-filter-group">
                        <legend>Allergènes à éviter</legend>
                        <div class="menu-filter-choice-grid">
                            <button class="menu-filter-choice" type="button" data-filter-chip="allergen" data-value="gluten">Gluten</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="allergen" data-value="lactose">Lactose</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="allergen" data-value="oeufs">Œufs</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="allergen" data-value="fruits-a-coque">Fruits à coque</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="allergen" data-value="crustaces">Crustacés</button>
                            <button class="menu-filter-choice" type="button" data-filter-chip="allergen" data-value="poisson">Poisson</button>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="menu-filter-dialog-actions">
                <button class="menu-filter-dialog-reset" type="button" data-filter-overlay-reset>Reinitialiser</button>
                <button class="menu-filter-dialog-submit" type="submit">
                    <span class="menu-filter-submit-label-full">Appliquer les filtres</span>
                    <span class="menu-filter-submit-label-short">Appliquer</span>
                </button>
            </div>
        </form>
    </section>
</div>
