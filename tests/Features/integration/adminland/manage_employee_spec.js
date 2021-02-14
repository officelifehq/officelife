let faker = require('faker');

describe('Adminland - Employee management', function () {
  it('should create an employee with different permission levels', function () {
    cy.login((userId) => {
      cy.createCompany((companyId) => {

        var firstname = faker.name.firstName();
        var lastname = faker.name.lastName();
        cy.createEmployee(firstname, lastname, faker.internet.email(), 'admin', false);
        cy.visit('/'+companyId+'/account/employees/all');
        cy.contains(firstname+' '+lastname);
        cy.hasAuditLog('Added '+firstname+' '+lastname+' as an employee', '/'+companyId+'/account/employees', companyId);

        firstname = faker.name.firstName();
        lastname = faker.name.lastName();
        cy.createEmployee(firstname, lastname, faker.internet.email(), 'hr', false);
        cy.visit('/'+companyId+'/account/employees/all');
        cy.contains(firstname+' '+lastname);
        cy.hasAuditLog('Added '+firstname+' '+lastname+' as an employee', '/'+companyId+'/account/employees', companyId);

        firstname = faker.name.firstName();
        lastname = faker.name.lastName();
        cy.createEmployee(firstname, lastname, faker.internet.email(), 'user', false);
        cy.visit('/'+companyId+'/account/employees/all');
        cy.contains(firstname+' '+lastname);
        cy.hasAuditLog('Added '+firstname+' '+lastname+' as an employee', '/'+companyId+'/account/employees', companyId);
      });
    });
  });

  it('should let a new admin create an account after being invited to use the app', function () {
    cy.login((userId) => {
      let companyName = faker.company.companyName();
      cy.createCompany(companyName, (companyId) => {

        var firstname = faker.name.firstName();
        var lastname = faker.name.lastName();
        cy.createEmployee(firstname, lastname, faker.internet.email(), 'admin', true);
        cy.visit('/'+companyId+'/account/employees/all');

        cy.get('[name="'+firstname+' '+lastname+'"]')
          .invoke('attr', 'data-invitation-link').then((link) => {
            cy.log('invitation link: '+link);
            cy.logout();
            cy.visit('/invite/employee/' + link);
            cy.get('[data-cy=accept-create-account]').click();
            cy.get('input[name=password]').type('admin1012');
            cy.get('[data-cy=create-cta]').click();
            cy.contains(companyName);
          });
      });
    });
  });

  it('should let a new hr create an account after being invited to use the app', function () {
    cy.login((userId) => {
      let companyName = faker.company.companyName();
      cy.createCompany(companyName, (companyId) => {

        var firstname = faker.name.firstName();
        var lastname = faker.name.lastName();
        cy.createEmployee(firstname, lastname, faker.internet.email(), 'hr', true);
        cy.visit('/'+companyId+'/account/employees/all');

        cy.get('[name="'+firstname+' '+lastname+'"]')
          .invoke('attr', 'data-invitation-link').then((link) => {
            cy.log('invitation link: '+link);
            cy.logout();
            cy.visit('/invite/employee/' + link);
            cy.get('[data-cy=accept-create-account]').click();
            cy.get('input[name=password]').type('admin1012');
            cy.get('[data-cy=create-cta]').click();
            cy.contains(companyName);
          });
      });
    });
  });

  it('should let a new user create an account after being invited to use the app', function () {
    cy.login((userId) => {
      let companyName = faker.company.companyName();
      cy.createCompany(companyName, (companyId) => {

        var firstname = faker.name.firstName();
        var lastname = faker.name.lastName();
        cy.createEmployee(firstname, lastname, faker.internet.email(), 'user', true);
        cy.visit('/'+companyId+'/account/employees/all');

        cy.get('[name="'+firstname+' '+lastname+'"]')
          .invoke('attr', 'data-invitation-link').then((link) => {
            cy.log('invitation link: '+link);
            cy.logout();
            cy.visit('/invite/employee/' + link);
            cy.get('[data-cy=accept-create-account]').click();
            cy.get('input[name=password]').type('admin1012');
            cy.get('[data-cy=create-cta]').click();
            cy.contains(companyName);
          });
      });
    });
  });

  it('should let an administrator delete an employee', function () {
    cy.login((userId) => {
      cy.createCompany((companyId) => {

        var firstname = faker.name.firstName();
        var lastname = faker.name.lastName();
        cy.createEmployee(firstname, lastname, faker.internet.email(), 'user', true, (id) => {

          // make sure the cancel button works
          cy.visit('/'+companyId+'/account/employees/'+id+'/delete');
          cy.get('[data-cy=cancel-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible');

          // delete the employee for real
          cy.visit('/'+companyId+'/account/employees/'+id+'/delete');
          cy.get('[data-cy=submit-delete-employee-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();

          cy.get('[data-cy=employee-list]').should('not.contain', firstname+' '+lastname);

          cy.hasAuditLog('Deleted the employee named '+firstname+' '+lastname, null, companyId);
        });
      });
    });
  });

  it('should let an HR delete an employee', function () {
    cy.login((userId) => {
      cy.createCompany((companyId) => {

        var firstname = faker.name.firstName();
        var lastname = faker.name.lastName();
        cy.createEmployee(firstname, lastname, faker.internet.email(), 'user', true, (id) => {

          cy.changePermission(userId, 200);

          // make sure the cancel button works
          cy.visit('/'+companyId+'/account/employees/'+id+'/delete');
          cy.get('[data-cy=cancel-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();

          // delete the employee for real
          cy.visit('/'+companyId+'/account/employees/'+id+'/delete');
          cy.get('[data-cy=submit-delete-employee-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();

          cy.get('[data-cy=employee-list]').should('not.contain', firstname+' '+lastname);
        });
      });
    });
  });

  it('should let an administrator lock and unlock an employee', function () {
    cy.login((userId) => {
      let companyName = faker.company.companyName();
      cy.createCompany(companyName, (companyId) => {

        var firstname = faker.name.firstName();
        var lastname = faker.name.lastName();
        var email = faker.internet.email();
        cy.createEmployee(firstname, lastname, email, 'user', true, (id) => {

          // logout and login the new employee to create the account
          cy.get('[name="'+firstname+' '+lastname+'"]')
            .invoke('attr', 'data-invitation-link').then((link) => {
              cy.log('invitation link: '+link);
              cy.logout();
              cy.visit('/invite/employee/' + link);
              cy.get('[data-cy=accept-create-account]').click();
              cy.get('input[name=password]').type('admin1012');
              cy.get('[data-cy=create-cta]').click();
              cy.contains(companyName);
              cy.logout();
            });

          // log back the admin to lock the account
          cy.loginin(userId);
          cy.visit('/'+companyId+'/account/employees/all');
          cy.get('[data-cy=unlock-account-'+id).should('not.exist');
          cy.get('[data-cy=lock-account-'+id).click();

          // make sure the cancel button works
          cy.get('[data-cy=cancel-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();
          cy.get('[data-cy=lock-account-'+id).click();

          // lock the employee for real
          cy.get('[data-cy=submit-lock-employee-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();
          cy.get('[data-cy=lock-status]').should('exist');

          cy.hasAuditLog('Locked the account of the employee named ', null, companyId);
          cy.logout();

          cy.get('input[name=email]').type(email);
          cy.get('input[name=password]').type('admin1012');
          cy.get('button[type=submit]').click();
          //cy.wait(1000)

          cy.exec('php artisan setup:verify-email').then((result) => {
            cy.visit('/home');
            cy.get('[data-cy=create-company-blank-state]', { timeout: 1000 }).should('exist');
            // logout and log the admin back to verify that the employee is not searchable anymore
            cy.logout();
          });

          cy.loginin(userId);

          // try to search the employee
          cy.visit('/'+companyId+'/dashboard');
          cy.get('[data-cy=header-find-link]').click();
          cy.get('input[name=search]').type(firstname);
          cy.get('[data-cy=header-find-submit]').click();
          cy.get('[data-cy=results]').should('not.contain', firstname);

          // try to search the employee in the add manager dropdown
          cy.visit('/'+companyId+'/employees/'+id);
          cy.get('[data-cy=add-hierarchy-button]').click();
          cy.get('[data-cy=add-manager-button]').click();
          cy.get('[data-cy=search-manager]').type(firstname);
          cy.get('[data-cy=results]').should('not.contain', firstname);

          // go to add hardware and see if the employee appears in the list of employees
          cy.get('[data-cy=header-adminland-link]').click();
          cy.get('[data-cy=hardware-admin-link]').click();
          cy.get('[data-cy=add-hardware-button]').click();
          cy.get('[data-cy=lend-hardware-checkbox]').check();
          cy.get('[data-cy=employee-selector]').click();
          cy.get('ul.vs__dropdown-menu>li').eq(1).should('not.exist');

          // now unlocking the account
          cy.visit('/'+companyId+'/account/employees/all');
          cy.get('[data-cy=lock-account-'+id).should('not.exist');
          cy.get('[data-cy=unlock-account-'+id).click();

          // make sure the cancel button works
          cy.get('[data-cy=cancel-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();
          cy.get('[data-cy=unlock-account-'+id).click();

          // unlock the employee
          cy.get('[data-cy=submit-unlock-employee-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();
          cy.get('[data-cy=lock-status]').should('not.exist');
          cy.hasAuditLog('Unlocked the account of the employee named ', null, companyId);
        });
      });
    });
  });

  it('should let an HR lock and unlock an employee', function () {
    cy.login((userId) => {
      let companyName = faker.company.companyName();
      cy.createCompany(companyName, (companyId) => {

        var firstname = faker.name.firstName();
        var lastname = faker.name.lastName();
        var email = faker.internet.email();
        cy.createEmployee(firstname, lastname, email, 'user', true, (id) => {

          cy.changePermission(userId, 200);

          // logout and login the new employee to create the account
          cy.get('[name="'+firstname+' '+lastname+'"]')
            .invoke('attr', 'data-invitation-link').then((link) => {
              cy.log('invitation link: '+link);
              cy.logout();
              cy.visit('/invite/employee/' + link);
              cy.get('[data-cy=accept-create-account]').click();
              cy.get('input[name=password]').type('admin1012');
              cy.get('[data-cy=create-cta]').click();
              cy.contains(companyName);
              cy.logout();
            });

          // log back the admin to lock the account
          cy.loginin(userId);
          cy.visit('/'+companyId+'/account/employees/all');
          cy.get('[data-cy=unlock-account-'+id).should('not.exist');
          cy.get('[data-cy=lock-account-'+id).click();

          // make sure the cancel button works
          cy.get('[data-cy=cancel-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();
          cy.get('[data-cy=lock-account-'+id).click();

          // lock the employee for real
          cy.get('[data-cy=submit-lock-employee-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();
          cy.get('[data-cy=lock-status]').should('exist');

          //cy.wait(1000)

          cy.logout();

          cy.get('input[name=email]').type(email);
          cy.get('input[name=password]').type('admin1012');
          cy.get('button[type=submit]').click();

          cy.exec('php artisan setup:verify-email').then((result) => {
            cy.visit('/home');
            cy.get('[data-cy=create-company-blank-state]', { timeout: 1000 }).should('exist');
            // logout and log the admin back to verify that the employee is not searchable anymore
            cy.logout();
          });

          cy.loginin(userId);

          // try to search the employee
          cy.visit('/'+companyId+'/dashboard');
          cy.get('[data-cy=header-find-link]').click();
          cy.get('input[name=search]').type(firstname);
          cy.get('[data-cy=header-find-submit]').click();
          cy.get('[data-cy=results]').should('not.contain', firstname);

          // try to search the employee in the add manager dropdown
          cy.visit('/'+companyId+'/employees/'+id);
          cy.get('[data-cy=add-hierarchy-button]').click();
          cy.get('[data-cy=add-manager-button]').click();
          cy.get('[data-cy=search-manager]').type(firstname);
          cy.get('[data-cy=results]').should('not.contain', firstname);

          // go to add hardware and see if the employee appears in the list of employees
          cy.get('[data-cy=header-adminland-link]').click();
          cy.get('[data-cy=hardware-admin-link]').click();
          cy.get('[data-cy=add-hardware-button]').click();
          cy.get('[data-cy=lend-hardware-checkbox]').check();
          cy.get('[data-cy=employee-selector]').click();
          cy.get('ul.vs__dropdown-menu>li').eq(1).should('not.exist');

          // now unlocking the account
          cy.visit('/'+companyId+'/account/employees/all');
          cy.get('[data-cy=lock-account-'+id).should('not.exist');
          cy.get('[data-cy=unlock-account-'+id).click();

          // make sure the cancel button works
          cy.get('[data-cy=cancel-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();
          cy.get('[data-cy=unlock-account-'+id).click();

          // unlock the employee
          cy.get('[data-cy=submit-unlock-employee-button').click();
          cy.get('@all-employee-link', {timeout: 500}).should('be.visible').click();
          cy.get('[data-cy=lock-status]').should('not.exist');
        });
      });
    });
  });
});
