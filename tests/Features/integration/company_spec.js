let faker = require('faker');

describe('Company - Basic company management', function () {
  it('should create a company', function () {
    cy.loginLegacy();

    var companyName = faker.company.companyName();
    cy.createCompany(companyName, (companyId) => {

      cy.contains('Thanks for joining');

      // check if the dashboard contains the company the user is part of
      cy.get('[data-cy=header-menu]').click();
      cy.get('[data-cy=switch-company-button]').click();
      cy.contains(companyName);
    });
  });

  it('should display a welcome message for the first administrator of a company', function () {
    cy.loginLegacy();

    var companyName = faker.company.companyName();
    cy.createCompany(companyName, (companyId) => {
      cy.url().should('include', '/welcome');

      // click on the hide message link
      cy.get('[data-cy=hide-message]').click();
      cy.url().should('include', '/dashboard');
      cy.get('[data-cy=header-desktop-welcome-tab]').should('not.exist');

      // create a new employee with the administrator right and it should not see this welcome message
      cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true, (employeeId) => {
        cy.get('[name=\'Michael Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
          cy.logout();
          cy.visit('/invite/employee/' + link);
          cy.get('[data-cy=accept-create-account]').click();
          cy.get('input[name=password]').type('admin1012');
          cy.get('[data-cy=create-cta]').click();
          cy.get('body').should('contain', companyName);
          cy.get('[data-cy=company-1]').click();

          cy.url().should('include', '/dashboard');
          cy.logout();
        });
      });

      // log back the original admin
      cy.visit('/login');
      cy.get('input[name=email]').type('admin@admin.com');
      cy.get('input[name=password]').type('admin');
      cy.get('button[type=submit]').click();
      cy.get(`[data-cy=company-${companyId}]`).click();

      // create a new employee with the hr right and it should not see this welcome message
      cy.createEmployee('John', 'Enroe', 'john.enroe@dundermifflin.com', 'hr', true, (employeeId) => {
        cy.get('[name=\'John Enroe\']').invoke('attr', 'data-invitation-link').then((link) => {
          cy.logout();
          cy.visit('/invite/employee/' + link);
          cy.get('[data-cy=accept-create-account]').click();
          cy.get('input[name=password]').type('admin1012');
          cy.get('[data-cy=create-cta]').click();
          cy.get('body').should('contain', companyName);
          cy.get(`[data-cy=company-${companyId}]`).click();

          cy.url().should('include', '/dashboard');
          cy.logout();
        });
      });

      // log back the original admin
      cy.visit('/login');
      cy.get('input[name=email]').type('admin@admin.com');
      cy.get('input[name=password]').type('admin');
      cy.get('button[type=submit]').click();
      cy.get(`[data-cy=company-${companyId}]`).click();

      // create a new employee with the user right and it should not see this welcome message
      cy.createEmployee('Henri', 'Scott', 'henri.scott3@dundermifflin.com', 'user', true, (employeeId) => {
        cy.visit('/'+companyId+'/account/employees/all');

        cy.get('[name=\'Henri Scott\']').invoke('attr', 'data-invitation-link').then((link) => {
          cy.logout();
          cy.visit('/invite/employee/' + link);
          cy.get('[data-cy=accept-create-account]').click();
          cy.get('input[name=password]').type('admin1012');
          cy.get('[data-cy=create-cta]').click();
          cy.get('body').should('contain', companyName);
          cy.get(`[data-cy=company-${companyId}]`).click();

          cy.url().should('include', '/dashboard');
        });
      });
    });
  });
});
