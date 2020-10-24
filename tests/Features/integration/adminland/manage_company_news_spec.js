let faker = require('faker');
let _ = require('lodash');

describe('Adminland - Company news management', function () {
  it('should let user access company news adminland screen with the right permissions', function () {
    cy.login((userId) => {

      cy.createCompany();

      cy.get('[data-cy=header-adminland-link]').click();
      cy.get('[data-cy=news-admin-link]', { timeout: 600 }).should('be.visible')
        .invoke('attr', 'href').then(function (url) {

          cy.canAccess(url, 100, 'Company news', userId);
          cy.canAccess(url, 200, 'Company news', userId);
          cy.canNotAccess(url, 300, userId);
        });
    });
  });

  it('should let you manage company news as an administrator', function () {
    cy.login();

    cy.createCompany((companyId) => {

      cy.get('[data-cy=header-adminland-link]').click();
      cy.get('[data-cy=news-admin-link]', { timeout: 600 }).should('be.visible')
        .invoke('attr', 'href').then(function (url) {

          cy.visit(url);

          // open the new page
          cy.get('[data-cy=add-news-button]').click();

          // start to populate it and press save
          var news = faker.lorem.words();
          cy.get('[data-cy=news-title-input').type(news);
          cy.get('[data-cy=news-content-textarea').type(faker.lorem.sentences());
          cy.get('[data-cy=submit-add-news-button]').click();

          // check to see if the data is there in the table
          cy.get('[data-cy=news-list]', {timeout: 500}).should('be.visible')
            .contains(news);
          cy.get('[data-cy=news-list]', {timeout: 500}).should('be.visible')
            .invoke('attr', 'data-cy-items').then(function (items) {
              let id = _.last(items.split(','));

              cy.hasAuditLog('Wrote a company news called '+news+'.', url, companyId);

              // access the row we just created to rename it
              cy.get('[data-cy=edit-news-button-'+id+']').click();
              cy.get('[data-cy=cancel-button]').click();
              cy.url().should('include', '/account/news');

              // go back to the news and edit it
              news = faker.lorem.words();
              cy.get('[data-cy=edit-news-button-'+id+']').click();
              cy.get('[data-cy=news-title-input').clear();
              cy.get('[data-cy=news-title-input').type(news);
              cy.get('[data-cy=news-content-textarea').clear();
              cy.get('[data-cy=news-content-textarea').type(faker.lorem.sentences());
              cy.get('[data-cy=submit-update-news-button]').click();
              cy.get('[data-cy=news-list]', {timeout: 1000}).should('be.visible');
              cy.hasAuditLog('Updated the news called '+news+'.', url, companyId);

              // delete the company news
              cy.get('[data-cy=list-delete-button-'+id+']', {timeout: 1000}).click();
              cy.get('[data-cy=list-delete-cancel-button-'+id+']').click();
              cy.get('[data-cy=list-delete-button-'+id+']').click();
              cy.get('[data-cy=list-delete-confirm-button-'+id+']').click();
              cy.get('[data-cy=news-list]').should('not.contain', news);
              cy.hasAuditLog('Destroyed the news called '+news+'.', null, companyId);
            });
        });
    });
  });

  it('should let you manage company news as an hr representative', function () {
    cy.login((userId) => {

      cy.changePermission(userId, 200);

      cy.createCompany((companyId) => {

        cy.get('[data-cy=header-adminland-link]').click();
        cy.get('[data-cy=news-admin-link]', { timeout: 800 }).should('be.visible')
          .invoke('attr', 'href').then(function (url) {

            cy.visit(url);

            // open the new page
            cy.get('[data-cy=add-news-button]').click();

            // start to populate it and press save
            var news = faker.lorem.words();
            cy.get('[data-cy=news-title-input').type(news);
            cy.get('[data-cy=news-content-textarea').type(faker.lorem.sentences());
            cy.get('[data-cy=submit-add-news-button]').click();

            // check to see if the data is there in the table
            cy.get('[data-cy=news-list]', {timeout: 500}).as('news-list').should('be.visible')
              .contains(news);
            cy.get('@news-list')
              .invoke('attr', 'data-cy-items').then(function (items) {
                let id = _.last(items.split(','));

                cy.hasAuditLog('Wrote a company news called '+news+'.', url, companyId);

                // access the row we just created to rename it
                cy.get('[data-cy=edit-news-button-'+id+']').click();
                cy.get('[data-cy=cancel-button]').click();
                cy.url().should('include', '/account/news');

                // go back to the news and edit it
                news = faker.lorem.words();
                cy.get('[data-cy=edit-news-button-'+id+']').click();
                cy.get('[data-cy=news-title-input').clear();
                cy.get('[data-cy=news-title-input').type(news);
                cy.get('[data-cy=news-content-textarea').clear();
                cy.get('[data-cy=news-content-textarea').type(faker.lorem.sentences());
                cy.get('[data-cy=submit-update-news-button]').click();
                cy.get('@news-list', {timeout: 10000}).should('be.visible');
                cy.hasAuditLog('Updated the news called '+news+'.', url, companyId);

                // delete the company news
                cy.get('[data-cy=list-delete-button-'+id+']', {timeout: 1000}).click();
                cy.get('[data-cy=list-delete-cancel-button-'+id+']').click();
                cy.get('[data-cy=list-delete-button-'+id+']').click();
                cy.get('[data-cy=list-delete-confirm-button-'+id+']').click();
                cy.get('@news-list').should('not.contain', news);
                cy.hasAuditLog('Destroyed the news called '+news+'.', null, companyId);
              });
          });
      });
    });
  });
});
