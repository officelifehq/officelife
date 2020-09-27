describe('Adminland - Expenses', function () {
  it('should let you manage expense categories as an administrator', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=expenses-admin-link]').click()

    // required because otherwise, categories don't load (wtf)
    cy.wait(2000)

    // open the popup
    cy.get('[data-cy=add-category-button]').click()

    // start to populate it and press save
    cy.get('[data-cy=add-title-input]').type('travel')
    cy.get('[data-cy=modal-add-cta]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=category-6]').contains('travel')
    cy.hasAuditLog('Created the expense category called travel', '/1/account/expenses')

    // rename the expense category
    cy.get('[data-cy=list-rename-button-6]').click()
    cy.get('[data-cy=list-rename-input-name-6]').clear()
    cy.get('[data-cy=list-rename-input-name-6]').type('house')
    cy.get('[data-cy=list-rename-cta-button-6]').click()
    cy.get('[data-cy=categories-list]').contains('house')
    cy.hasAuditLog('Updated the expense category’s name from', '/1/account/expenses')

    // delete the expense category
    cy.get('[data-cy=list-delete-button-6]').click()
    cy.get('[data-cy=list-delete-confirm-button-6]').click()
    cy.hasAuditLog('Deleted the expense category', '/1/account/expenses')
  })

  it('should let you manage expense categories as an HR', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200)
    })

    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=expenses-admin-link]').click()

    // open the popup
    cy.get('[data-cy=add-category-button]').click()
    cy.wait(2000)

    // start to populate it and press save
    cy.get('[data-cy=add-title-input]').type('travel')
    cy.get('[data-cy=modal-add-cta]').click()

    // check to see if the data is there in the table
    cy.get('[data-cy=categories-list]').contains('travel')

    // rename the expense category
    cy.get('[data-cy=list-rename-button-6]').click()
    cy.get('[data-cy=list-rename-input-name-6]').clear()
    cy.get('[data-cy=list-rename-input-name-6]').type('house')
    cy.get('[data-cy=list-rename-cta-button-6]').click()
    cy.get('[data-cy=categories-list]').contains('house')

    // delete the expense category
    cy.get('[data-cy=list-delete-button-6]').click()
    cy.get('[data-cy=list-delete-confirm-button-6]').click()
  })

  it('should let you manage accountants as an administrator', function () {
    cy.loginLegacy()

    cy.createCompany()

    // check that the user, regardless of their roles, has not access to the
    // expenses tab on the dashboard
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-expenses-tab]').should('not.exist')

    // go to the adminland and manage accountants
    cy.grantAccountantRight('admin', 1)

    // check to see if the list of accountants contains the newly added employee
    cy.get('[data-cy=employees-list]').contains('admin@admin.com')

    cy.hasAuditLog('Allowed admin@admin.com to manage company’s expense', '/1/account/expenses')
    cy.hasEmployeeLog('Has been allowed to manage company’s expenses', '/1/dashboard')
    cy.hasNotification('You are now allowed to manage company’s expenses')

    // check that the dashboard contains the expenses tab
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-expenses-tab]').should('exist')

    // go to the adminland to remove the accountant
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=expenses-admin-link]').click()
    cy.get('[data-cy=show-edit-mode]').click()
    cy.get('[data-cy=remove-employee-1]').click()
    cy.hasAuditLog('Disallowed admin@admin.com to manage company’s expense', '/1/account/expenses')
    cy.hasEmployeeLog('Has been disallowed to manage company’s expenses', '/1/dashboard')
  })

  it('should let you manage accountants as HR', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.get('body').invoke('attr', 'data-account-id').then(function (userId) {
      cy.changePermission(userId, 200)
    })

    // check that the user, regardless of their roles, has not access to the
    // expenses tab on the dashboard
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-expenses-tab]').should('not.exist')

    // go to the adminland and manage accountants
    cy.grantAccountantRight('admin', 1)

    // check to see if the list of accountants contains the newly added employee
    cy.get('[data-cy=employees-list]').contains('admin@admin.com')

    cy.hasEmployeeLog('Has been allowed to manage company’s expenses', '/1/dashboard')
    cy.hasNotification('You are now allowed to manage company’s expenses')

    // check that the dashboard contains the expenses tab
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-expenses-tab]').should('exist')

    // go to the adminland to remove the accountant
    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=expenses-admin-link]').click()
    cy.get('[data-cy=show-edit-mode]').click()
    cy.get('[data-cy=remove-employee-1]').click()
    cy.hasEmployeeLog('Has been disallowed to manage company’s expenses', '/1/dashboard')
  })
})
