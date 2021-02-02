describe('Employee - manage contract information', function () {
  it('should let an admin edit contract information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    // make sure the dashboard doesn't include any information about contract renewal date
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=contract-renewal]').should('not.exist');

    // make sure the employee profile page doesn't contain any information about the contract renewal date
    cy.visit('/1/employees/1');
    cy.get('[data-cy=employee-contract-renewal-date]').should('not.exist');
    cy.get('[data-cy=employee-contract-rate]').should('not.exist');

    // make sure the edit profile page doesn't show the "Contract" tab
    cy.visit('/1/employees/1/edit');
    cy.get('[data-cy=menu-contract-link]').should('not.exist');

    // create an employee status
    cy.createEmployeeStatus(1, 'consultant', true);

    // assign the status to the employee
    cy.get('[data-cy=statuses-list]', { timeout: 500 })
      .invoke('attr', 'data-cy-items').then(function (items) {
        let id = _.last(items.split(','));

        cy.visit('/1/employees/1');
        cy.get('[data-cy=edit-status-button]').click();
        cy.get('[data-cy=list-status-' + id + ']').click();

        // now check that the Edit contract tab is available in the Edit profile page
        cy.visit('/1/employees/1/edit');
        cy.get('[data-cy=menu-contract-link]').should('exist');
        cy.get('[data-cy=menu-contract-link]').click();
        cy.setContractRenewalDate(1, 1, 3);
        cy.setContractRate(1, 1, 100);

        // check on the employee profile page that the date is there
        cy.visit('/1/employees/1');
        cy.get('[data-cy=employee-contract-renewal-date]').should('exist');
        cy.get('[data-cy=employee-contract-rate]').should('exist');

        // the contract renewal date is very close - it should also appear on the dashboard
        cy.visit('/1/dashboard/me');
        cy.get('[data-cy=contract-renewal]').should('exist');

        // change the contract renewal date in a very far future and the date shouldn't appear on the dashboard
        cy.setContractRenewalDate(1, 1, 35);

        cy.visit('/1/dashboard/me');
        cy.get('[data-cy=contract-renewal]').should('not.exist');

        cy.visit('/1/employees/1/contract/edit');

        // destroy the contract rate
        cy.get('[data-cy=contract-rates-list]', { timeout: 500 })
          .invoke('attr', 'data-cy-items').then(function (items) {
            let rateId = _.last(items.split(','));

            cy.get('[data-cy=list-delete-button-' + rateId + ']').click();
            cy.get('[data-cy=list-delete-confirm-button-' + rateId + ']').click();

            cy.get('[data-cy=rate-item-' + rateId + ']').should('not.exist');
          });

        // check that the renewal date is not displayed anymore on the employee profile page
        cy.get('[data-cy=employee-contract-renewal-date]').should('not.exist');
        cy.get('[data-cy=employee-contract-rate]').should('not.exist');

        // remove the contract renewal date entirely
        cy.visit('/1/employees/1');
        cy.get('[data-cy=edit-status-button').click();
        cy.get('[data-cy=status-reset-button]').click();

        // check that the renewal date is not displayed anymore on the employee dashboard
        cy.visit('/1/dashboard/me');
        cy.get('[data-cy=contract-renewal]').should('not.exist');
      });

    cy.visit('/1/employees/1');
  });

  it('should let an HR edit contract information', function () {
    cy.loginLegacy();

    cy.createCompany();

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 200);

    // make sure the dashboard doesn't include any information about contract renewal date
    cy.visit('/1/dashboard/me');
    cy.get('[data-cy=contract-renewal]').should('not.exist');

    // make sure the employee profile page doesn't contain any information about the contract renewal date
    cy.visit('/1/employees/1');
    cy.get('[data-cy=employee-contract-renewal-date]').should('not.exist');
    cy.get('[data-cy=employee-contract-rate]').should('not.exist');

    // make sure the edit profile page doesn't show the "Contract" tab
    cy.visit('/1/employees/1/edit');
    cy.get('[data-cy=menu-contract-link]').should('not.exist');

    // create an employee status
    cy.createEmployeeStatus(1, 'consultant', true);

    // assign the status to the employee
    cy.get('[data-cy=statuses-list]', { timeout: 500 })
      .invoke('attr', 'data-cy-items').then(function (items) {
        let id = _.last(items.split(','));

        cy.visit('/1/employees/1');
        cy.get('[data-cy=edit-status-button]').click();
        cy.get('[data-cy=list-status-' + id + ']').click();

        // now check that the Edit contract tab is available in the Edit profile page
        cy.visit('/1/employees/1/edit');
        cy.get('[data-cy=menu-contract-link]').should('exist');
        cy.get('[data-cy=menu-contract-link]').click();
        cy.setContractRenewalDate(1, 1, 3);
        cy.setContractRate(1, 1, 100);

        // check on the employee profile page that the date is there
        cy.visit('/1/employees/1');
        cy.get('[data-cy=employee-contract-renewal-date]').should('exist');
        cy.get('[data-cy=employee-contract-rate]').should('exist');

        // the contract renewal date is very close - it should also appear on the dashboard
        cy.visit('/1/dashboard/me');
        cy.get('[data-cy=contract-renewal]').should('exist');

        // change the contract renewal date in a very far future and the date shouldn't appear on the dashboard
        cy.setContractRenewalDate(1, 1, 35);

        cy.visit('/1/dashboard/me');
        cy.get('[data-cy=contract-renewal]').should('not.exist');

        cy.visit('/1/employees/1/contract/edit');

        // destroy the contract rate
        cy.get('[data-cy=contract-rates-list]', { timeout: 500 })
          .invoke('attr', 'data-cy-items').then(function (items) {
            let rateId = _.last(items.split(','));

            cy.get('[data-cy=list-delete-button-' + rateId + ']').click();
            cy.get('[data-cy=list-delete-confirm-button-' + rateId + ']').click();

            cy.get('[data-cy=rate-item-' + rateId + ']').should('not.exist');
          });

        // check that the renewal date is not displayed anymore on the employee profile page
        cy.get('[data-cy=employee-contract-renewal-date]').should('not.exist');
        cy.get('[data-cy=employee-contract-rate]').should('not.exist');

        // remove the contract renewal date entirely
        cy.visit('/1/employees/1');
        cy.get('[data-cy=edit-status-button').click();
        cy.get('[data-cy=status-reset-button]').click();

        // check that the renewal date is not displayed anymore on the employee dashboard
        cy.visit('/1/dashboard/me');
        cy.get('[data-cy=contract-renewal]').should('not.exist');
      });

    cy.visit('/1/employees/1');
  });

  it('should not let a normal user edit contract information', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);
    cy.changePermission(1, 300);

    cy.visit('/1/employees/1/contract/edit');

    cy.url().should('include', '/home');
  });

  it('should not let a normal user edit someone elses contract information', function () {
    cy.loginLegacy();

    cy.createCompany();
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true);

    cy.changePermission(1, 300);

    cy.visit('/1/employees/2/contract/edit');

    cy.url().should('include', '/home');
  });
});
