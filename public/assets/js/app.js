/*
 * Interactions vanilla JavaScript de Vite & Gourmand.
 */

const updateHomeHeader = () => {
    if (
        !document.body.classList.contains('page-home')
        && !document.body.classList.contains('page-menus')
    ) {
        return;
    }

    document.body.classList.toggle('header-scrolled', window.scrollY > 40);
};

const updateBackToTop = () => {
    document.body.classList.toggle('show-back-to-top', window.scrollY > 360);
};

window.addEventListener('scroll', () => {
    updateHomeHeader();
    updateBackToTop();
});
updateHomeHeader();
updateBackToTop();

const mobileMenu = document.querySelector('[data-mobile-menu]');
const mobileMenuOpenButton = document.querySelector('[data-mobile-menu-open]');
const mobileMenuCloseButton = document.querySelector('[data-mobile-menu-close]');

const closeMobileMenu = () => {
    if (!(mobileMenu instanceof HTMLElement)) {
        return;
    }

    mobileMenu.hidden = true;
    document.body.classList.remove('mobile-menu-open');

    if (mobileMenuOpenButton instanceof HTMLElement) {
        mobileMenuOpenButton.setAttribute('aria-expanded', 'false');
        mobileMenuOpenButton.focus();
    }
};

const openMobileMenu = () => {
    if (!(mobileMenu instanceof HTMLElement)) {
        return;
    }

    mobileMenu.hidden = false;
    document.body.classList.add('mobile-menu-open');

    if (mobileMenuOpenButton instanceof HTMLElement) {
        mobileMenuOpenButton.setAttribute('aria-expanded', 'true');
    }

    if (mobileMenuCloseButton instanceof HTMLElement) {
        mobileMenuCloseButton.focus();
    }
};

if (mobileMenuOpenButton instanceof HTMLElement) {
    mobileMenuOpenButton.addEventListener('click', openMobileMenu);
}

if (mobileMenuCloseButton instanceof HTMLElement) {
    mobileMenuCloseButton.addEventListener('click', closeMobileMenu);
}

if (mobileMenu instanceof HTMLElement) {
    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeMobileMenu);
    });
}

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && mobileMenu instanceof HTMLElement && !mobileMenu.hidden) {
        closeMobileMenu();
    }
});

const accountNav = document.querySelector('[data-client-account-nav]');

if (accountNav instanceof HTMLElement) {
    const accountNavLinks = Array.from(accountNav.querySelectorAll('[data-account-nav-link]'));

    const updateAccountNavState = () => {
        const currentHash = window.location.hash || '#mes-commandes';

        accountNavLinks.forEach((link) => {
            if (!(link instanceof HTMLAnchorElement)) {
                return;
            }

            const isActive = link.getAttribute('href') === currentHash;
            link.classList.toggle('is-active', isActive);

            if (isActive) {
                link.setAttribute('aria-current', 'page');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    window.addEventListener('hashchange', updateAccountNavState);
    updateAccountNavState();
}

const homeMenuFilters = document.querySelector('[data-home-menu-filters]');

if (homeMenuFilters instanceof HTMLElement) {
    const homeFilterButtons = Array.from(homeMenuFilters.querySelectorAll('[data-home-menu-filter]'));
    const homeMenuCards = Array.from(document.querySelectorAll('[data-home-menu-card]'));
    const allFilterButton = homeFilterButtons.find((button) => button.dataset.homeMenuFilter === 'all' && !button.classList.contains('home-menu-filter-all'));

    const matchesHomeMenuFilter = (card, selectedFilter) => {
        if (selectedFilter === 'available') {
            return card.dataset.available === '1';
        }

        if (selectedFilter === 'party') {
            return card.dataset.party === '1';
        }

        if (selectedFilter === 'large') {
            return Number(card.dataset.people || 0) > 6;
        }

        if (selectedFilter === 'budget') {
            return Number(card.dataset.price || 0) < 150;
        }

        return true;
    };

    const applyHomeMenuFilter = (selectedFilter) => {
        homeMenuCards.forEach((card) => {
            card.hidden = !matchesHomeMenuFilter(card, selectedFilter);
        });
    };

    homeFilterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const selectedFilter = button.dataset.homeMenuFilter || 'all';
            const activeButton = button.classList.contains('home-menu-filter-all')
                ? allFilterButton
                : button;

            homeFilterButtons.forEach((filterButton) => {
                const isActive = filterButton === activeButton;
                filterButton.classList.toggle('is-active', isActive);
                filterButton.setAttribute('aria-pressed', String(isActive));
            });

            applyHomeMenuFilter(selectedFilter);
        });
    });
}

const menuFilterForm = document.querySelector('[data-menu-filters]');

if (menuFilterForm instanceof HTMLFormElement) {
    const menuCards = Array.from(document.querySelectorAll('[data-menu-card]'));
    const resultCount = document.querySelector('[data-menu-results-count]');
    const emptyState = document.querySelector('[data-menu-empty-state]');
    const quickButtons = Array.from(menuFilterForm.querySelectorAll('[data-quick-filter]'));
    const overlayOpenButton = menuFilterForm.querySelector('[data-filter-overlay-open]');
    const overlay = document.querySelector('[data-filter-overlay]');
    const overlayForm = overlay?.querySelector('[data-filter-overlay-form]');
    const overlayCloseButtons = overlay === null
        ? []
        : Array.from(overlay.querySelectorAll('[data-filter-overlay-close]'));
    const overlayResetButton = overlay?.querySelector('[data-filter-overlay-reset]');
    const filterChips = overlay === null
        ? []
        : Array.from(overlay.querySelectorAll('[data-filter-chip]'));
    const overlayDialog = overlay?.querySelector('.menu-filter-dialog');
    const overlayScrollable = overlay?.querySelector('.menu-filter-dialog-grid');
    const advancedFields = overlay === null
        ? {}
        : {
            theme: overlay.querySelector('[data-advanced-filter="theme"]'),
            regime: overlay.querySelector('[data-advanced-filter="regime"]'),
            minPrice: overlay.querySelector('[data-advanced-filter="minPrice"]'),
            maxPrice: overlay.querySelector('[data-advanced-filter="maxPrice"]'),
            peopleMin: overlay.querySelector('[data-advanced-filter="peopleMin"]'),
            peopleMax: overlay.querySelector('[data-advanced-filter="peopleMax"]'),
            availability: overlay.querySelector('[data-advanced-filter="availability"]'),
            allergens: overlay.querySelector('[data-advanced-filter="allergens"]'),
            seafood: overlay.querySelector('[data-advanced-filter="seafood"]'),
        };
    let selectedQuickFilter = 'all';
    let advancedFilters = {
        theme: '',
        regime: '',
        minPrice: '',
        maxPrice: '',
        peopleMin: '',
        peopleMax: '',
        availability: '',
        allergens: '',
        seafood: '',
    };

    const matchesQuickFilter = (card) => {
        if (selectedQuickFilter === 'available') {
            return card.dataset.available === '1';
        }

        if (selectedQuickFilter === 'party') {
            return card.dataset.party === '1';
        }

        if (selectedQuickFilter === 'large') {
            return Number(card.dataset.people || 0) > 6;
        }

        if (selectedQuickFilter === 'budget') {
            return Number(card.dataset.price || 0) < 150;
        }

        return true;
    };

    const matchesAdvancedFilters = (card) => {
        if (advancedFilters.theme !== '' && card.dataset.themeId !== advancedFilters.theme) {
            return false;
        }

        if (advancedFilters.regime !== '' && card.dataset.regimeId !== advancedFilters.regime) {
            return false;
        }

        const minPrice = Number(advancedFilters.minPrice || 0);
        const maxPrice = Number(advancedFilters.maxPrice || 0);
        const cardPrice = Number(card.dataset.price || 0);

        if (minPrice > 0 && cardPrice < minPrice) {
            return false;
        }

        if (maxPrice > 0 && cardPrice > maxPrice) {
            return false;
        }

        const peopleMin = Number(advancedFilters.peopleMin || 0);
        const peopleMax = Number(advancedFilters.peopleMax || 0);
        const cardPeople = Number(card.dataset.people || 0);

        if (peopleMin > 0 && cardPeople < peopleMin) {
            return false;
        }

        if (peopleMax > 0 && cardPeople > peopleMax) {
            return false;
        }

        if (advancedFilters.availability === 'available' && card.dataset.available !== '1') {
            return false;
        }

        if (advancedFilters.availability === 'limited' && card.dataset.statusType !== 'limited') {
            return false;
        }

        if (advancedFilters.availability === 'week' && card.dataset.statusWeek !== '1') {
            return false;
        }

        const cardAllergens = (card.dataset.allergens || '').split(' ').filter(Boolean);
        const allergensToAvoid = advancedFilters.allergens.split(',').filter(Boolean);

        if (allergensToAvoid.some((allergen) => cardAllergens.includes(allergen))) {
            return false;
        }

        if (advancedFilters.seafood === '1' && !cardAllergens.some((allergen) => ['poisson', 'crustaces'].includes(allergen))) {
            return false;
        }

        return true;
    };

    const hasAdvancedFilters = () => Object.values(advancedFilters).some((value) => value !== '');

    const updateOverlayButtonState = () => {
        if (overlayOpenButton instanceof HTMLElement) {
            overlayOpenButton.classList.toggle('has-filters', hasAdvancedFilters());
        }
    };

    const syncAdvancedFiltersFromFields = () => {
        Object.entries(advancedFields).forEach(([filterName, field]) => {
            if (field instanceof HTMLInputElement || field instanceof HTMLSelectElement) {
                advancedFilters[filterName] = field.value.trim();
            }
        });
    };

    const resetAdvancedFields = () => {
        Object.values(advancedFields).forEach((field) => {
            if (field instanceof HTMLInputElement || field instanceof HTMLSelectElement) {
                field.value = '';
            }
        });

        advancedFilters = {
            theme: '',
            regime: '',
            minPrice: '',
            maxPrice: '',
            peopleMin: '',
            peopleMax: '',
            availability: '',
            allergens: '',
            seafood: '',
        };

        filterChips.forEach((button) => {
            button.classList.remove('is-selected');
            button.setAttribute('aria-pressed', 'false');
        });
    };

    const setAdvancedFieldValue = (filterName, value) => {
        const field = advancedFields[filterName];

        if (field instanceof HTMLInputElement || field instanceof HTMLSelectElement) {
            field.value = value;
        }
    };

    const toggleSingleChoice = (button) => {
        const filterName = button.dataset.filterChip || '';
        const wasSelected = button.classList.contains('is-selected');

        filterChips
            .filter((chip) => chip.dataset.filterChip === filterName)
            .forEach((chip) => {
                chip.classList.remove('is-selected');
                chip.setAttribute('aria-pressed', 'false');
            });

        if (!wasSelected) {
            button.classList.add('is-selected');
            button.setAttribute('aria-pressed', 'true');
        }

        return !wasSelected;
    };

    const updateAllergenField = () => {
        const selectedAllergens = filterChips
            .filter((chip) => chip.dataset.filterChip === 'allergen' && chip.classList.contains('is-selected'))
            .map((chip) => chip.dataset.value || '')
            .filter(Boolean);

        setAdvancedFieldValue('allergens', selectedAllergens.join(','));
    };

    const activateQuickButton = (buttonToActivate) => {
        quickButtons.forEach((quickButton) => {
            const isActive = quickButton === buttonToActivate;
            quickButton.classList.toggle('is-active', isActive);
            quickButton.setAttribute('aria-pressed', String(isActive));
        });
    };

    const resetQuickFilter = () => {
        selectedQuickFilter = 'all';

        const allButton = quickButtons.find((button) => button.dataset.quickFilter === 'all');

        if (allButton instanceof HTMLElement) {
            activateQuickButton(allButton);
        }
    };

    const applyMenuFilters = () => {
        let visibleCount = 0;

        menuCards.forEach((card) => {
            const isVisible = matchesQuickFilter(card) && matchesAdvancedFilters(card);

            card.hidden = !isVisible;

            if (isVisible) {
                visibleCount += 1;
            }
        });

        if (resultCount !== null) {
            resultCount.textContent = `${visibleCount} menu${visibleCount > 1 ? 's' : ''} affiche${visibleCount > 1 ? 's' : ''}`;
        }

        if (emptyState instanceof HTMLElement) {
            emptyState.hidden = visibleCount > 0;
        }

        updateOverlayButtonState();
    };

    const closeFilterOverlay = () => {
        if (overlay instanceof HTMLElement) {
            overlay.hidden = true;
            document.body.classList.remove('filter-overlay-open');
        }

        if (overlayOpenButton instanceof HTMLElement) {
            overlayOpenButton.setAttribute('aria-expanded', 'false');
            overlayOpenButton.focus();
        }
    };

    const openFilterOverlay = () => {
        if (!(overlay instanceof HTMLElement)) {
            return;
        }

        overlay.hidden = false;
        document.body.classList.add('filter-overlay-open');
        overlayDialog?.classList.remove('is-scrolled');

        if (overlayScrollable instanceof HTMLElement) {
            overlayScrollable.scrollTop = 0;
        }

        if (overlayOpenButton instanceof HTMLElement) {
            overlayOpenButton.setAttribute('aria-expanded', 'true');
        }

        const firstField = overlay.querySelector('button, select, input:not([type="hidden"])');

        if (firstField instanceof HTMLElement) {
            firstField.focus();
        }
    };

    menuFilterForm.addEventListener('submit', (event) => {
        event.preventDefault();
        applyMenuFilters();
    });

    quickButtons.forEach((button) => {
        button.addEventListener('click', () => {
            selectedQuickFilter = button.dataset.quickFilter || 'all';
            activateQuickButton(button);

            applyMenuFilters();
        });
    });

    filterChips.forEach((button) => {
        button.setAttribute('aria-pressed', 'false');

        button.addEventListener('click', () => {
            const filterName = button.dataset.filterChip || '';

            if (filterName === 'allergen') {
                const isSelected = !button.classList.contains('is-selected');
                button.classList.toggle('is-selected', isSelected);
                button.setAttribute('aria-pressed', String(isSelected));
                updateAllergenField();
                syncAdvancedFiltersFromFields();
                updateOverlayButtonState();

                return;
            }

            const isSelected = toggleSingleChoice(button);

            if (filterName === 'budget') {
                setAdvancedFieldValue('minPrice', isSelected ? button.dataset.minPrice || '' : '');
                setAdvancedFieldValue('maxPrice', isSelected ? button.dataset.maxPrice || '' : '');
            }

            if (filterName === 'people') {
                setAdvancedFieldValue('peopleMin', isSelected ? button.dataset.minPeople || '' : '');
                setAdvancedFieldValue('peopleMax', isSelected ? button.dataset.maxPeople || '' : '');
            }

            if (['theme', 'regime', 'availability', 'seafood'].includes(filterName)) {
                setAdvancedFieldValue(filterName, isSelected ? button.dataset.value || '' : '');
            }

            syncAdvancedFiltersFromFields();
            updateOverlayButtonState();
        });
    });

    if (overlayOpenButton instanceof HTMLElement) {
        overlayOpenButton.addEventListener('click', openFilterOverlay);
    }

    if (overlayForm instanceof HTMLFormElement) {
        overlayForm.addEventListener('submit', (event) => {
            event.preventDefault();
            syncAdvancedFiltersFromFields();
            applyMenuFilters();
            closeFilterOverlay();
        });
    }

    if (overlayResetButton instanceof HTMLElement) {
        overlayResetButton.addEventListener('click', () => {
            resetAdvancedFields();
            resetQuickFilter();
            applyMenuFilters();
        });
    }

    overlayCloseButtons.forEach((button) => {
        button.addEventListener('click', closeFilterOverlay);
    });

    if (overlayScrollable instanceof HTMLElement) {
        overlayScrollable.addEventListener('scroll', () => {
            overlayDialog?.classList.toggle('is-scrolled', overlayScrollable.scrollTop > 60);
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && overlay instanceof HTMLElement && !overlay.hidden) {
            closeFilterOverlay();
        }

        if (event.key === 'Escape' && mobileMenu instanceof HTMLElement && !mobileMenu.hidden) {
            closeMobileMenu();
        }
    });

    applyMenuFilters();
}

const orderForm = document.querySelector('[data-order-form]');

if (orderForm instanceof HTMLFormElement) {
    const menuSelect = orderForm.querySelector('[data-order-menu]');
    const peopleInput = orderForm.querySelector('[data-order-people]');
    const cityInput = orderForm.querySelector('[data-order-city]');
    const distanceInput = orderForm.querySelector('[data-order-distance]');
    const distanceField = orderForm.querySelector('[data-order-distance-field]');
    const orderFormLayout = orderForm.closest('.client-order-form-layout') ?? orderForm.parentElement;
    const preview = orderFormLayout?.querySelector('[data-order-preview]');
    const previewMenu = orderFormLayout?.querySelector('[data-order-preview-menu]');
    const previewDiscount = orderFormLayout?.querySelector('[data-order-preview-discount]');
    const previewDelivery = orderFormLayout?.querySelector('[data-order-preview-delivery]');
    const previewTotal = orderFormLayout?.querySelector('[data-order-preview-total]');
    const previewMessage = orderFormLayout?.querySelector('[data-order-preview-message]');
    const previewTitle = orderFormLayout?.querySelector('[data-order-preview-title]');
    const previewDescription = orderFormLayout?.querySelector('[data-order-preview-description]');
    const previewUnit = orderFormLayout?.querySelector('[data-order-preview-unit]');
    const previewPeople = orderFormLayout?.querySelector('[data-order-preview-people]');
    const previewMinimum = orderFormLayout?.querySelector('[data-order-preview-minimum]');
    const previewStock = orderFormLayout?.querySelector('[data-order-preview-stock]');
    const conditionsCard = orderForm.querySelector('[data-order-menu-conditions]');
    const conditionsText = orderForm.querySelector('[data-order-menu-conditions-text]');

    const formatCurrency = (value) => new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: Number.isInteger(value) ? 0 : 2,
        maximumFractionDigits: 2,
    }).format(value);

    const pluralizePeople = (value) => `${value} personne${value > 1 ? 's' : ''}`;

    const pluralizeStock = (value) => `${value} commande${value > 1 ? 's' : ''} restante${value > 1 ? 's' : ''}`;

    const updatePreviewText = (element, value) => {
        if (element instanceof HTMLElement) {
            element.textContent = value;
        }
    };

    const updatePreviewValue = (element, value) => {
        if (element instanceof HTMLElement) {
            element.textContent = formatCurrency(value);
        }
    };

    const updatePreviewMessage = (message) => {
        if (previewMessage instanceof HTMLElement) {
            previewMessage.textContent = message;
            return;
        }

        if (preview instanceof HTMLElement) {
            preview.textContent = message;
        }
    };

    const updateDistanceVisibility = () => {
        if (!(cityInput instanceof HTMLInputElement) || !(distanceInput instanceof HTMLInputElement)) {
            return;
        }

        const isBordeaux = cityInput.value.trim().toLowerCase() === 'bordeaux';

        if (distanceField instanceof HTMLElement) {
            distanceField.classList.toggle('is-hidden', isBordeaux);
        }

        distanceInput.disabled = isBordeaux;

        if (isBordeaux) {
            distanceInput.value = '0';
        }
    };

    const updateOrderPreview = () => {
        if (
            !(peopleInput instanceof HTMLInputElement)
            || !(cityInput instanceof HTMLInputElement)
            || !(distanceInput instanceof HTMLInputElement)
            || !(preview instanceof HTMLElement)
        ) {
            return;
        }

        const selectedOption = menuSelect instanceof HTMLSelectElement ? menuSelect.selectedOptions[0] : null;
        const minimumPeople = Number(selectedOption?.dataset.min || orderForm.dataset.orderFixedMin || 0);
        const minimumPrice = Number(selectedOption?.dataset.price || orderForm.dataset.orderFixedPrice || 0);
        const selectedTitle = selectedOption?.dataset.title || selectedOption?.textContent?.trim() || 'Choisir un menu';
        const selectedDescription = selectedOption?.dataset.description || 'Le récapitulatif se mettra à jour selon votre sélection.';
        const selectedConditions = selectedOption?.dataset.conditions || 'Aucune condition particulière renseignée pour ce menu.';
        const stock = Number(selectedOption?.dataset.stock || 0);
        const people = Number(peopleInput.value || 0);
        const distance = Math.max(0, Number(distanceInput.value || 0));
        const city = cityInput.value.trim().toLowerCase();
        const isBordeaux = city === 'bordeaux';
        const hasSelectedMenu = selectedOption instanceof HTMLOptionElement && selectedOption.value !== '';

        if (minimumPeople > 0) {
            peopleInput.min = String(minimumPeople);
        }

        updatePreviewText(previewTitle, hasSelectedMenu ? selectedTitle : 'Choisir un menu');
        updatePreviewText(previewDescription, hasSelectedMenu ? selectedDescription : 'Le récapitulatif se mettra à jour selon votre sélection.');
        updatePreviewText(previewMinimum, minimumPeople > 0 ? pluralizePeople(minimumPeople) : '0 personne');
        updatePreviewText(previewStock, hasSelectedMenu ? pluralizeStock(stock) : '0 commande restante');

        if (conditionsCard instanceof HTMLElement) {
            conditionsCard.classList.toggle('is-empty', !hasSelectedMenu);
        }

        updatePreviewText(
            conditionsText,
            hasSelectedMenu ? selectedConditions : 'Sélectionnez un menu pour afficher les conditions importantes de commande.'
        );

        if (minimumPeople <= 0 || minimumPrice <= 0 || people <= 0) {
            updatePreviewText(previewUnit, '0 € / pers.');
            updatePreviewText(previewPeople, String(Math.max(0, people)));
            updatePreviewValue(previewMenu, 0);
            updatePreviewText(previewDiscount, '- 0 €');
            updatePreviewValue(previewDelivery, 0);
            updatePreviewValue(previewTotal, 0);
            updatePreviewMessage('Sélectionnez un menu et un nombre de personnes pour afficher le prix estimé.');
            return;
        }

        const pricePerPerson = minimumPrice / minimumPeople;
        const menuPrice = pricePerPerson * people;
        const discount = people >= minimumPeople + 5 ? menuPrice * 0.10 : 0;
        const delivery = isBordeaux ? 0 : 5 + (distance * 0.59);
        const total = menuPrice - discount + delivery;

        updatePreviewText(previewUnit, `${formatCurrency(pricePerPerson)} / pers.`);
        updatePreviewText(previewPeople, String(people));
        updatePreviewValue(previewMenu, menuPrice);
        updatePreviewText(previewDiscount, `- ${formatCurrency(discount)}`);
        updatePreviewValue(previewDelivery, delivery);
        updatePreviewValue(previewTotal, total);
        if (people < minimumPeople) {
            updatePreviewMessage(`Le minimum requis pour ce menu est de ${pluralizePeople(minimumPeople)}.`);
        } else if (!isBordeaux && distance <= 0) {
            updatePreviewMessage('Hors Bordeaux, indiquez une distance approximative pour estimer la livraison. L’équipe vérifiera l’adresse avant validation.');
        } else if (discount > 0) {
            updatePreviewMessage('Une remise de 10 % est appliquée car le nombre de personnes dépasse le minimum du menu.');
        } else {
            updatePreviewMessage('Le total reste indicatif jusqu’à validation par l’équipe.');
        }
    };

    const refreshOrderForm = () => {
        updateDistanceVisibility();
        updateOrderPreview();
    };

    orderForm.addEventListener('input', refreshOrderForm);
    orderForm.addEventListener('change', refreshOrderForm);
    refreshOrderForm();
}

const clientRating = document.querySelector('[data-client-rating]');

if (clientRating instanceof HTMLElement) {
    const ratingInputs = Array.from(clientRating.querySelectorAll('input[type="radio"]'));
    const ratingLabels = Array.from(clientRating.querySelectorAll('[data-rating-value]'));

    const setRatingPreview = (value) => {
        ratingLabels.forEach((label) => {
            const ratingValue = Number(label.dataset.ratingValue || 0);
            label.classList.toggle('is-selected', ratingValue > 0 && ratingValue <= value);
        });
    };

    const checkedInput = ratingInputs.find((input) => input instanceof HTMLInputElement && input.checked);
    setRatingPreview(checkedInput instanceof HTMLInputElement ? Number(checkedInput.value) : 0);

    ratingInputs.forEach((input) => {
        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        input.addEventListener('change', () => {
            setRatingPreview(Number(input.value));
        });
    });

    ratingLabels.forEach((label) => {
        if (!(label instanceof HTMLElement)) {
            return;
        }

        label.addEventListener('mouseenter', () => {
            setRatingPreview(Number(label.dataset.ratingValue || 0));
        });
    });

    clientRating.addEventListener('mouseleave', () => {
        const currentInput = ratingInputs.find((input) => input instanceof HTMLInputElement && input.checked);
        setRatingPreview(currentInput instanceof HTMLInputElement ? Number(currentInput.value) : 0);
    });
}
