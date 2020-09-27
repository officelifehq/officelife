describe('Employee - edit birthdate', function () {
  it('should let an admin see and edit birthdate', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)
    cy.visit('/1/employees/2')

    // edit the profile
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

    cy.wait(1000)

    // check logs
    cy.hasAuditLog('Set the birthdate of', '/1/employees/2')
    cy.hasEmployeeLog('Set the birthdate', '/1/employees/2', '/1/employees/2/logs')

    // check that we display the full date of the employee as an admin can see
    // the full date
    cy.visit('/1/employees/2')
    cy.get('[data-cy=employee-birthdate-information]').contains('Mar 10, 1981 (age: ')
  })

  it('should let a HR see and edit birthdate', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)
    cy.visit('/1/employees/2')

    cy.changePermission(1, 200)

    // edit the profile
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

    // check logs
    cy.hasEmployeeLog('Set the birthdate', '/1/employees/2', '/1/employees/2/logs')

    // check that we display the full date of the employee as an HR can see
    // the full date
    cy.visit('/1/employees/2')
    cy.get('[data-cy=employee-birthdate-information]').contains('Mar 10, 1981 (age: ')
  })

  it('should let a normal user see and edit his own birthdate', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.visit('/1/employees/1')

    cy.changePermission(1, 300)

    // edit the profile
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()
    cy.get('input[name=firstname]').type('dwight')
    cy.get('input[name=lastname]').type('schrute')
    cy.get('input[name=email]').clear()
    cy.get('input[name=email]').type('dwight@dundermifflin.com')
    cy.get('input[name=year]').type('1981')
    cy.get('input[name=month]').type('3')
    cy.get('input[name=day]').type('10')
    cy.get('[data-cy=submit-edit-employee-button]').click()

    // check logs
    cy.hasEmployeeLog('Set the birthdate', '/1/employees/1', '/1/employees/1/logs')

    // check that we display the full date of the employee as an HR can see
    // the full date
    cy.get('[data-cy=employee-birthdate-information]').contains('Mar 10, 1981 (age: ')
  })

  it('should not let a normal user edit someone elses birthdate but he can see a partial birthdate', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)

    cy.visit('/1/employees/2/edit')

    // edit the profile
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

    cy.changePermission(1, 300)
    cy.visit('/1/employees/2')

    cy.get('[data-cy=edit-profile-button]').should('not.exist')

    // check that we display the partial date of the employee
    cy.get('[data-cy=employee-birthdate-information]').contains('March 10th')
  })
})
