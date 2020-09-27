var faker = require('faker');

describe('Adminland - Employee management', function () {
  it('should create an employee with different permission levels', function () {
    cy.login((userId) => {
      cy.createCompany()

      var firstname = faker.name.firstName()
      var lastname = faker.name.lastName()
      cy.createEmployee(firstname, lastname, faker.internet.email(), 'admin', false)
      cy.visit('/${userId}/account/employees/all')
      cy.contains(firstname + ' ' + lastname)
      cy.hasAuditLog('Added '+firstname+' '+lastname+' as an employee', '/'+userId+'/account/employees')

      firstname = faker.name.firstName()
      lastname = faker.name.lastName()
      cy.createEmployee(firstname, lastname, faker.internet.email(), 'hr', false)
      cy.createEmployee('Dwight', 'Schrute', 'dwight.schrute@dundermifflin.com', 'hr', false)
      cy.visit('/'+userId+'/account/employees/all')
      cy.contains(firstname + ' ' + lastname)
      cy.hasAuditLog('Added '+firstname+' '+lastname+' as an employee', '/'+userId+'/account/employees')

      firstname = faker.name.firstName()
      lastname = faker.name.lastName()
      cy.createEmployee(firstname, lastname, faker.internet.email(), 'user', false)
      cy.visit('/'+userId+'/account/employees/all')
      cy.contains(firstname + ' ' + lastname)
      cy.hasAuditLog('Added '+firstname+' '+lastname+' as an employee', '/'+userId+'/account/employees')
    })
  })

  it('should let a new admin create an account after being invited to use the app', function () {
    cy.login((userId) => {
      cy.createCompany()

      var firstname = faker.name.firstName()
      var lastname = faker.name.lastName()
      cy.createEmployee(firstname, lastname, faker.internet.email(), 'admin', true)
      cy.visit('/'+userId+'/account/employees/all')

      cy.get("[name='"+firstname+" "+lastname+"']").invoke('attr', 'data-invitation-link').then((link) => {
        cy.logout()
        cy.visit('/invite/employee/' + link)
        cy.get('[data-cy=accept-create-account]').click()
        cy.get('input[name=password]').type('admin1012')
        cy.get('[data-cy=create-cta]').click()
        cy.get('body').should('contain', firstname + ' ' + lastname)
      })
    })
  })

  it('should let a new hr create an account after being invited to use the app', function () {
    cy.login((userId) => {
      cy.createCompany()

      var firstname = faker.name.firstName()
      var lastname = faker.name.lastName()
      cy.createEmployee(firstname, lastname, faker.internet.email(), 'hr', true)
      cy.visit('/'+userId+'/account/employees/all')

      cy.get("[name='"+firstname+" "+lastname+"']").invoke('attr', 'data-invitation-link').then((link) => {
        cy.logout()
        cy.visit('/invite/employee/' + link)
        cy.get('[data-cy=accept-create-account]').click()
        cy.get('input[name=password]').type('admin1012')
        cy.get('[data-cy=create-cta]').click()
        cy.get('body').should('contain', firstname + ' ' + lastname)
      })
    })
  })

  it('should let a new user create an account after being invited to use the app', function () {
    cy.login((userId) => {
      cy.createCompany()
      cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)
      cy.visit('/'+userId+'/account/employees/all')

      cy.get("[name='Michael Scott']").invoke('attr', 'data-invitation-link').then((link) => {
        cy.logout()
        cy.visit('/invite/employee/' + link)
        cy.get('[data-cy=accept-create-account]').click()
        cy.get('input[name=password]').type('admin1012')
        cy.get('[data-cy=create-cta]').click()
        cy.get('body').should('contain', 'Dunder Mifflin')
      })
    })
  })

  it('should let an administrator delete an employee', function () {
    cy.loginLegacy()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)

    cy.visit('/'+userId+'/account/employees/2/delete')

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.visit('/'+userId+'/account/employees/2/delete')

    // delete the employee for real
    cy.get('[data-cy=submit-delete-employee-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=employee-list]').should('not.contain', 'Michael Scott')

    cy.wait(1000)

    cy.hasAuditLog('Deleted the employee named Michael Scott', '/'+userId+'/account/employees')
  })

  it('should let an HR delete an employee', function () {
    cy.loginLegacy()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200)
    })

    cy.visit('/'+userId+'/account/employees/2/delete')

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.visit('/'+userId+'/account/employees/2/delete')

    // delete the employee for real
    cy.get('[data-cy=submit-delete-employee-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.get('[data-cy=employee-list]').should('not.contain', 'Michael Scott')
  })

  it('should let an administrator lock and unlock an employee', function () {
    cy.loginLegacy()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)
    cy.visit('/'+userId+'/account/employees/all')

    // logout and login the new employee to create the account
    cy.get("[name='Michael Scott']").invoke('attr', 'data-invitation-link').then((link) => {
      cy.logout()
      cy.visit('/invite/employee/' + link)
      cy.get('[data-cy=accept-create-account]').click()
      cy.get('input[name=password]').type('admin1012')
      cy.get('[data-cy=create-cta]').click()
      cy.get('body').should('contain', 'Dunder Mifflin')
      cy.logout()
    })

    // log back the admin to lock the account
    cy.get('input[name=email]').type('admin@admin.com')
    cy.get('input[name=password]').type('admin')
    cy.get('button[type=submit]').click()
    cy.wait(1000)
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=unlock-account-2').should('not.exist')
    cy.get('[data-cy=lock-account-2').click()

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=lock-account-2').click()

    // lock the employee for real
    cy.get('[data-cy=submit-lock-employee-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=lock-status]').should('exist')

    cy.wait(1000)

    cy.hasAuditLog('Locked the account of the employee named ', '/'+userId+'/account/employees')
    cy.logout()

    cy.get('input[name=email]').type('michael.scott@dundermifflin.com')
    cy.get('input[name=password]').type('admin1012')
    cy.get('button[type=submit]').click()
    cy.wait(1000)

    cy.get('[data-cy=create-company-blank-state]').should('exist')

    // logout and log the admin back to verify that the employee is not searchable anymore
    cy.logout()
    cy.get('input[name=email]').type('admin@admin.com')
    cy.get('input[name=password]').type('admin')
    cy.get('button[type=submit]').click()
    cy.wait(1000)

    // try to search the employee
    cy.visit('/'+userId+'/dashboard')
    cy.get('[data-cy=header-find-link]').click()
    cy.get('input[name=search]').type('scott')
    cy.get('[data-cy=header-find-submit]').click()
    cy.get('[data-cy=results]').should('not.contain', 'scott')

    // try to search the employee in the add manager dropdown
    cy.visit('/'+userId+'/employees/1')
    cy.get('[data-cy=add-hierarchy-button]').click()
    cy.get('[data-cy=add-manager-button]').click()
    cy.get('[data-cy=search-manager]').type('scott')
    cy.get('[data-cy=results]').should('not.contain', 'scott')

    // go to add hardware and see if the employee appears in the list of employees
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=hardware-admin-link]').click()
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(1).should('not.exist')

    // now unlocking the account
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=lock-account-2').should('not.exist')
    cy.get('[data-cy=unlock-account-2').click()

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=unlock-account-2').click()

    // unlock the employee
    cy.get('[data-cy=submit-unlock-employee-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.get('[data-cy=lock-status]').should('not.exist')
    cy.hasAuditLog('Unlocked the account of the employee named ', '/'+userId+'/account/employees')
  })

  it('should let an HR lock and unlock an employee', function () {
    cy.loginLegacy()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200)
    })
    cy.visit('/'+userId+'/account/employees/all')

    // logout and login the new employee to create the account
    cy.get("[name='Michael Scott']").invoke('attr', 'data-invitation-link').then((link) => {
      cy.logout()
      cy.visit('/invite/employee/' + link)
      cy.get('[data-cy=accept-create-account]').click()
      cy.get('input[name=password]').type('admin1012')
      cy.get('[data-cy=create-cta]').click()
      cy.get('body').should('contain', 'Dunder Mifflin')
      cy.logout()
    })

    // log back the admin to lock the account
    cy.get('input[name=email]').type('admin@admin.com')
    cy.get('input[name=password]').type('admin')
    cy.get('button[type=submit]').click()
    cy.wait(1000)
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=unlock-account-2').should('not.exist')
    cy.get('[data-cy=lock-account-2').click()

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=lock-account-2').click()

    // lock the employee for real
    cy.get('[data-cy=submit-lock-employee-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=lock-status]').should('exist')

    cy.wait(1000)

    cy.logout()

    cy.get('input[name=email]').type('michael.scott@dundermifflin.com')
    cy.get('input[name=password]').type('admin1012')
    cy.get('button[type=submit]').click()
    cy.wait(1000)

    cy.get('[data-cy=create-company-blank-state]').should('exist')

    // logout and log the admin back to verify that the employee is not searchable anymore
    cy.logout()
    cy.get('input[name=email]').type('admin@admin.com')
    cy.get('input[name=password]').type('admin')
    cy.get('button[type=submit]').click()
    cy.wait(1000)

    // try to search the employee
    cy.visit('/'+userId+'/dashboard')
    cy.get('[data-cy=header-find-link]').click()
    cy.get('input[name=search]').type('scott')
    cy.get('[data-cy=header-find-submit]').click()
    cy.get('[data-cy=results]').should('not.contain', 'scott')

    // try to search the employee in the add manager dropdown
    cy.visit('/'+userId+'/employees/1')
    cy.get('[data-cy=add-hierarchy-button]').click()
    cy.get('[data-cy=add-manager-button]').click()
    cy.get('[data-cy=search-manager]').type('scott')
    cy.get('[data-cy=results]').should('not.contain', 'scott')

    // go to add hardware and see if the employee appears in the list of employees
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=hardware-admin-link]').click()
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(1).should('not.exist')

    // now unlocking the account
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=lock-account-2').should('not.exist')
    cy.get('[data-cy=unlock-account-2').click()

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.visit('/'+userId+'/account/employees/all')
    cy.get('[data-cy=unlock-account-2').click()

    // unlock the employee
    cy.get('[data-cy=submit-unlock-employee-button').click()
    cy.url().should('include', '/'+userId+'/account/employees')
    cy.get('[data-cy=lock-status]').should('not.exist')
  })
})
