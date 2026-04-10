document.addEventListener('DOMContentLoaded', function () {
    var questionList = document.querySelector('[data-question-list]');
    var template = document.getElementById('quiz-question-template');
    var addButton = document.querySelector('[data-add-question]');

    if (!questionList || !template) {
        return;
    }

    var refreshQuestionNumbers = function () {
        questionList.querySelectorAll('[data-question-card]').forEach(function (card, index) {
            var badge = card.querySelector('.question-order');
            if (badge) {
                badge.textContent = 'Q' + (index + 1);
            }
        });
    };

    var syncPanels = function (card) {
        var typeSelect = card.querySelector('[data-question-type]');
        var type = typeSelect ? typeSelect.value : 'single_choice';

        card.querySelectorAll('[data-type-panel]').forEach(function (panel) {
            panel.hidden = panel.dataset.typePanel !== type;
        });
    };

    var wireCard = function (card) {
        var typeSelect = card.querySelector('[data-question-type]');
        var removeInput = card.querySelector('[data-remove-input]');
        var removedLabel = card.querySelector('[data-removed-label]');
        var restoreButton = card.querySelector('[data-question-restore]');
        var removeButton = card.querySelector('[data-question-remove]');

        if (typeSelect) {
            typeSelect.addEventListener('change', function () { syncPanels(card); });
            syncPanels(card);
        }

        if (removeButton) {
            removeButton.addEventListener('click', function () {
                if (card.dataset.persisted === '1') {
                    if (removeInput) {
                        removeInput.value = '1';
                    }
                    card.classList.add('is-removed');
                    if (removedLabel) {
                        removedLabel.hidden = false;
                    }
                    if (restoreButton) {
                        restoreButton.hidden = false;
                    }
                    return;
                }

                card.remove();
                refreshQuestionNumbers();
            });
        }

        if (restoreButton) {
            restoreButton.addEventListener('click', function () {
                if (removeInput) {
                    removeInput.value = '0';
                }
                card.classList.remove('is-removed');
                if (removedLabel) {
                    removedLabel.hidden = true;
                }
                restoreButton.hidden = true;
            });
        }
    };

    questionList.querySelectorAll('[data-question-card]').forEach(wireCard);
    refreshQuestionNumbers();

    if (addButton) {
        addButton.addEventListener('click', function () {
            var nextIndex = Number(questionList.dataset.nextIndex || questionList.children.length);
            var html = template.innerHTML.replaceAll('__INDEX__', String(nextIndex));

            questionList.insertAdjacentHTML('beforeend', html);
            questionList.dataset.nextIndex = String(nextIndex + 1);

            var newCard = questionList.lastElementChild;
            if (newCard) {
                wireCard(newCard);
            }

            refreshQuestionNumbers();
        });
    }
});
