describe('Employee - manage hiring date information', function () {
  it('should let an admin edit hiring date information', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)
    cy.visit('/1/employees/2')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()

    cy.get('input[name=firstname]').type('dwight')
    cy.get('input[name=lastname]').type('schrute')
    cy.get('input[name=email]').clear()
    cy.get('input[name=email]').type('dwight@dundermifflin.com')
    cy.get('input[name=year]').type('1981')
    cy.get('input[name=month]').type('3')
    cy.get('input[name=day]').type('10')
    cy.get('input[name=hired_at_year]').type('1981')
    cy.get('input[name=hired_at_month]').type('3')
    cy.get('input[name=hired_at_day]').type('10')
    cy.get('[data-cy=submit-edit-employee-button]').click()

    cy.get('[data-cy=hired-at-date]').contains('Mar 10, 1981')

    cy.hasAuditLog('Set the hiring date', '/1/employees/2')
    cy.hasEmployeeLog('Set the hiring date', '/1/employees/2', '/1/employees/2/logs')
  })

  it('should let an HR edit hiring date information', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.changePermission(1, 200)

    cy.visit('/1/employees/2')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()

    cy.get('input[name=firstname]').type('dwight')
    cy.get('input[name=lastname]').type('schrute')
    cy.get('input[name=email]').clear()
    cy.get('input[name=email]').type('dwight@dundermifflin.com')
    cy.get('input[name=year]').type('1981')
    cy.get('input[name=month]').type('3')
    cy.get('input[name=day]').type('10')
    cy.get('input[name=hired_at_year]').type('1981')
    cy.get('input[name=hired_at_month]').type('3')
    cy.get('input[name=hired_at_day]').type('10')
    cy.get('[data-cy=submit-edit-employee-button]').click()

    cy.get('[data-cy=hired-at-date]').contains('Mar 10, 1981')

    cy.hasEmployeeLog('Set the hiring date', '/1/employees/2', '/1/employees/2/logs')
  })

  it('should let not a normal user edit his own hiring date information', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.changePermission(1, 300)
    cy.visit('/1/employees/1')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()
    cy.get('[data-cy=hired-at-information]').should('not.exist')
  })

  it('should not let a normal user edit someone elses hiring date information', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.changePermission(1, 300)

    cy.visit('/1/employees/2/edit')

    cy.url().should('include', '/home')
  })
})
