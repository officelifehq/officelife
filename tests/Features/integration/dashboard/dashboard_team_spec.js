describe('Dashboard - teams', function () {
  it.skip('should display an empty tab when not associated with a team', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=dashboard-team-tab]').click()

    cy.contains('You are not associated with a team at the moment')
  })

  it.skip('should display the list of teams if the employee is associated with at least one team', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createTeam('product')
    cy.createTeam('sales')

    // assign a first team
    cy.visit('/1/employees/1')

    cy.get('[data-cy=open-team-modal-blank]').click()
    cy.get('[data-cy=list-team-1]').click()

    cy.visit('/1/dashboard')

    cy.get('[data-cy=dashboard-team-tab]').click()

    cy.contains('What your team has done this week')

    // assign a second team
    cy.visit('/1/employees/1')
    cy.get('[data-cy=open-team-modal]').click()
    cy.get('[data-cy=list-team-2]').click()

    cy.visit('/1/dashboard')

    cy.get('[data-cy=dashboard-team-tab]').click()

    cy.contains('What your team has done this week')
    cy.contains('Viewing')
    cy.contains('product')
    cy.contains('sales')
  })

  it.skip('should display the upcoming birthdays of employees on the team dashboard', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)
    cy.assignEmployeeToTeam(1, 1)

    // visit the dashboard, the team tab and find that the birthday is empty
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-team-tab]').click()
    cy.get('[data-cy=team-birthdate-blank]').should('exist')

    // edit the user birthdate
    cy.visit('/1/employees/1')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()
    cy.get('input[name=firstname]').type('dwight')
    cy.get('input[name=lastname]').type('schrute')
    cy.get('input[name=email]').clear()
    cy.get('input[name=email]').type('dwight@dundermifflin.com')
    cy.get('input[name=year]').type('1981')
    cy.get('input[name=month]').type(Cypress.moment().add(2, 'days').month() + 1)
    cy.get('input[name=day]').type(Cypress.moment().add(2, 'days').date())
    cy.get('[data-cy=submit-edit-employee-button]').click()

    // now, on the dashboard team tab, there should be a birthdate
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-team-tab]').click()
    cy.get('[data-cy=team-birthdate-blank]').should('not.exist')
    cy.get('[data-cy=birthdays-list]').contains('dwight schrute')

    // change the birthdate and make sure the birthdate doesn't appear anymore
    // edit the user birthdate
    cy.visit('/1/employees/1')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()
    cy.get('input[name=month]').clear()
    cy.get('input[name=month]').type(Cypress.moment().add(40, 'days').month() + 1)
    cy.get('input[name=day]').clear()
    cy.get('input[name=day]').type(Cypress.moment().add(40, 'days').date())
    cy.get('[data-cy=submit-edit-employee-button]').click()

    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-team-tab]').click()
    cy.get('[data-cy=team-birthdate-blank]').should('exist')
  })

  it.skip('should display the employees of this team who work from home today', function () {
    cy.loginLegacy()

    cy.createCompany()
    cy.createTeam('product')

    cy.wait(1000)
    cy.assignEmployeeToTeam(1, 1)

    // visit the dashboard, the team tab and find that the birthday is empty
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-team-tab]').click()
    cy.get('[data-cy=team-work-from-home-blank]').should('exist')

    // indicate that the employee works from home
    cy.visit('/1/dashboard/me')
    cy.get('[data-cy=log-from-work-home-cta]').check()

    // now, on the dashboard team tab, there should be the employee indicating
    // that he works from home
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-team-tab]').click()
    cy.get('[data-cy=team-work-from-home-blank]').should('not.exist')
    cy.get('[data-cy=work-from-home-list]').contains('admin@admin.com')
  })

  it('should display upcoming new hires in this team', function () {
    cy.loginLegacy()

    cy.createCompany()

    cy.createTeam('product')

    cy.wait(1000)
    cy.assignEmployeeToTeam(1, 1)

    // check that there are no futures hiring date
    cy.visit('/1/dashboard/team')
    cy.get('[data-cy=new-hires-list]').should('not.exist')

    // set the hiring date
    cy.visit('/1/employees/1')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=show-edit-view]').click()

    const tomorrowDate = Cypress.moment().add(1, 'days')

    cy.get('input[name=firstname]').type('dwight')
    cy.get('input[name=lastname]').type('schrute')
    cy.get('input[name=email]').clear()
    cy.get('input[name=email]').type('dwight@dundermifflin.com')
    cy.get('input[name=year]').type('1981')
    cy.get('input[name=month]').type('3')
    cy.get('input[name=day]').type('10')
    cy.get('input[name=hired_at_year]').type(tomorrowDate.year())
    cy.get('input[name=hired_at_month]').type(tomorrowDate.month() + 1)
    cy.get('input[name=hired_at_day]').type(tomorrowDate.date())
    cy.get('[data-cy=submit-edit-employee-button]').click()

    // visit the dashboard, the team tab and find that the birthday is empty
    cy.visit('/1/dashboard')
    cy.get('[data-cy=dashboard-team-tab]').click()
    cy.get('[data-cy=new-hires-list]').should('exist')
    cy.get('[data-cy=new-hires-list]').contains('dwight')
  })
})
