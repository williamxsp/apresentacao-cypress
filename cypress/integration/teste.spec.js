/// <reference types="Cypress" />

context('Todos', () => {
    describe('Interface', () => {
        it('Todos os campos estão presentes', () => {
            cy.visit('');
            const li = cy.get('li');
            li.should('have.length', 5)
                .get('input[type="text"]')
                .should('be.visible');
            li.get('button')
                .should('be.visible')
                .should('contain', 'Remover');
            cy.get('[data-js="nova-tarefa"]')
                .should('be.visible')
                .should('contain', 'Nova Tarefa');
        });
    });

    describe('Gerenciar Todos', () => {
        it.only('Marcar Todo como finalizado', () => {
            cy.server()
                .route('POST', 'http://127.0.0.1:8123/**')
                .as('atualizar');

            cy.server()
                .route('GET', 'http://127.0.0.1:8123/**')
                .as('listar');

            cy.visit('');
            cy.get('li')
                .first()
                .find('input[type="checkbox"]')
                .click();
            cy.wait('@atualizar');
            cy.wait('@listar');
            cy.wait(200);
            cy.get('li')
                .first()
                .find('input[type="checkbox"]')
                .should('be.checked');

            cy.get('li')
                .first()
                .find('input[type="checkbox"]')
                .click();
            cy.wait('@atualizar');
            cy.wait('@listar');
            cy.wait(200);
            cy.get('li')
                .first()
                .find('input[type="checkbox"]')
                .should('not.be.checked');
        });
    });
});
