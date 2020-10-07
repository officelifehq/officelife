describe('Adminland - Employee management', function () {
  it('should create an employee with different permission levels', function () {
    cy.login()
    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', false)
    cy.visit('/1/account/employees/all')
    cy.contains('Michael Scott')
    cy.hasAuditLog('Added Michael Scott as an employee', '/1/account/employees')

    cy.createEmployee('Dwight', 'Schrute', 'dwight.schrute@dundermifflin.com', 'hr', false)
    cy.visit('/1/account/employees/all')
    cy.contains('Dwight Schrute')
    cy.hasAuditLog('Added Dwight Schrute as an employee', '/1/account/employees')

    cy.createEmployee('Jim', 'Halpert', 'jim.halpert@dundermifflin.com', 'user', false)
    cy.visit('/1/account/employees/all')
    cy.contains('Jim Halpert')
    cy.hasAuditLog('Added Jim Halpert as an employee', '/1/account/employees')
  })

  it('should let a new admin create an account after being invited to use the app', function () {
    cy.login()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)
    cy.visit('/1/account/employees/all')

    cy.get("[name='Michael Scott']").invoke('attr', 'data-invitation-link').then((link) => {
      cy.logout()
      cy.visit('/invite/employee/' + link)
      cy.get('[data-cy=accept-create-account]').click()
      cy.get('input[name=password]').type('admin1012')
      cy.get('[data-cy=create-cta]').click()
      cy.get('body').should('contain', 'Dunder Mifflin')
    })
  })

  it('should let a new hr create an account after being invited to use the app', function () {
    cy.login()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'hr', true)
    cy.visit('/1/account/employees/all')

    cy.get("[name='Michael Scott']").invoke('attr', 'data-invitation-link').then((link) => {
      cy.logout()
      cy.visit('/invite/employee/' + link)
      cy.get('[data-cy=accept-create-account]').click()
      cy.get('input[name=password]').type('admin1012')
      cy.get('[data-cy=create-cta]').click()
      cy.get('body').should('contain', 'Dunder Mifflin')
    })
  })

  it('should let a new user create an account after being invited to use the app', function () {
    cy.login()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)
    cy.visit('/1/account/employees/all')

    cy.get("[name='Michael Scott']").invoke('attr', 'data-invitation-link').then((link) => {
      cy.logout()
      cy.visit('/invite/employee/' + link)
      cy.get('[data-cy=accept-create-account]').click()
      cy.get('input[name=password]').type('admin1012')
      cy.get('[data-cy=create-cta]').click()
      cy.get('body').should('contain', 'Dunder Mifflin')
    })
  })

  it('should let an administrator delete an employee', function () {
    cy.login()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)

    cy.visit('/1/account/employees/2/delete')

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.visit('/1/account/employees/2/delete')

    // delete the employee for real
    cy.get('[data-cy=submit-delete-employee-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=employee-list]').should('not.contain', 'Michael Scott')

    cy.wait(1000)

    cy.hasAuditLog('Deleted the employee named Michael Scott', '/1/account/employees')
  })

  it('should let an HR delete an employee', function () {
    cy.login()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)

    cy.changePermission(1, 200)

    cy.visit('/1/account/employees/2/delete')

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.visit('/1/account/employees/2/delete')

    // delete the employee for real
    cy.get('[data-cy=submit-delete-employee-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.get('[data-cy=employee-list]').should('not.contain', 'Michael Scott')
  })

  it('should let an administrator lock and unlock an employee', function () {
    cy.login()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)
    cy.visit('/1/account/employees/all')

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
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=unlock-account-2').should('not.exist')
    cy.get('[data-cy=lock-account-2').click()

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=lock-account-2').click()

    // lock the employee for real
    cy.get('[data-cy=submit-lock-employee-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=lock-status]').should('exist')

    cy.wait(1000)

    cy.hasAuditLog('Locked the account of the employee named ', '/1/account/employees')
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
    cy.visit('/1/dashboard')
    cy.get('[data-cy=header-find-link]').click()
    cy.get('input[name=search]').type('scott')
    cy.get('[data-cy=header-find-submit]').click()
    cy.get('[data-cy=results]').should('not.contain', 'scott')

    // try to search the employee in the add manager dropdown
    cy.visit('/1/employees/1')
    cy.get('[data-cy=add-hierarchy-button]').click()
    cy.get('[data-cy=add-manager-button]').click()
    cy.get('[data-cy=search-manager]').type('scott')
    cy.get('[data-cy=results]').should('not.contain', 'scott')

    // go to add hardware and see if the employee appears in the list of employees
    cy.visit('/1/account')
    cy.get('[data-cy=hardware-admin-link]').click()
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(1).should('not.exist')

    // now unlocking the account
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=lock-account-2').should('not.exist')
    cy.get('[data-cy=unlock-account-2').click()

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=unlock-account-2').click()

    // unlock the employee
    cy.get('[data-cy=submit-unlock-employee-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.get('[data-cy=lock-status]').should('not.exist')
    cy.hasAuditLog('Unlocked the account of the employee named ', '/1/account/employees')
  })

  it('should let an HR lock and unlock an employee', function () {
    cy.login()
    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)

    cy.changePermission(1, 200)
    cy.visit('/1/account/employees/all')

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
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=unlock-account-2').should('not.exist')
    cy.get('[data-cy=lock-account-2').click()

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=lock-account-2').click()

    // lock the employee for real
    cy.get('[data-cy=submit-lock-employee-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.visit('/1/account/employees/all')
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
    cy.visit('/1/dashboard')
    cy.get('[data-cy=header-find-link]').click()
    cy.get('input[name=search]').type('scott')
    cy.get('[data-cy=header-find-submit]').click()
    cy.get('[data-cy=results]').should('not.contain', 'scott')

    // try to search the employee in the add manager dropdown
    cy.visit('/1/employees/1')
    cy.get('[data-cy=add-hierarchy-button]').click()
    cy.get('[data-cy=add-manager-button]').click()
    cy.get('[data-cy=search-manager]').type('scott')
    cy.get('[data-cy=results]').should('not.contain', 'scott')

    // go to add hardware and see if the employee appears in the list of employees
    cy.visit('/1/account')
    cy.get('[data-cy=hardware-admin-link]').click()
    cy.get('[data-cy=add-hardware-button]').click()
    cy.get('[data-cy=lend-hardware-checkbox]').check()
    cy.get('[data-cy=employee-selector]').click()
    cy.get('ul.vs__dropdown-menu>li').eq(1).should('not.exist')

    // now unlocking the account
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=lock-account-2').should('not.exist')
    cy.get('[data-cy=unlock-account-2').click()

    // make sure the cancel button works
    cy.get('[data-cy=cancel-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.visit('/1/account/employees/all')
    cy.get('[data-cy=unlock-account-2').click()

    // unlock the employee
    cy.get('[data-cy=submit-unlock-employee-button').click()
    cy.url().should('include', '/1/account/employees')
    cy.get('[data-cy=lock-status]').should('not.exist')
  })
})
